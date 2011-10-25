<?php
/**
 * Basic ExtJS class
 *
 * Loads classes based on class naming.
 *
 * @category	meetidaaa.com
 * @package		Lib
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 *
 */

/**
 * ExtJS Basic Adapter class
 *
 * @category	meetidaaa.com
 * @package		Lib
 *
 */
class Lib_Ext
{

	/**
	 * Namespace
	 *
	 * @var string
	 */
	protected $_namespace	= 'bas';

	/**
	 * Router URL
	 *
	 * @var string
	 */
	protected $_routerUrl	= 'ext/router';

	/**
	 * Api definition
	 *
	 * @var array
	 */
	protected $_servicePath = 'application/App/Service/Ext';

	/**
	 * Service package prefix
	 *
	 * @var string
	 */
	protected $_servicePackage = 'App_Service_Ext';

	/**
	 * Method suffix for ExtJS form handling
	 *
	 * @var string
	 */
	protected $_formHandlerSuffix = 'Form';

	/**
	 * Api definition
	 *
	 * @var array $API
	 */
	protected $_api = array();

	/**
	 * perform a single RCP call
	 *
	 * A more public function description.\n
	 * A more public function description.\n
	 * A more public function description.\n
	 *
	 * @throws Exception on undefined actions
	 *
	 * @param StdClass $cdata Data on which to perform the rpc call
	 *
	 * @return array An array containing method call result data
	 */
	protected function _doRpc($cdata)
	{

		try {

			if (!isset($this->_api[$cdata->action])) {

				throw new Exception(
					'Call to undefined action: ' . $cdata->action
				);
			}

			$action = $cdata->action;
			$apiAction = $this->_api[$action];

			$this->_doAroundCalls($apiAction['before'], $cdata);

			$method = $cdata->method;
			$mdef = $apiAction['methods'][$method];

			$methodCallName = $method;
			if (array_key_exists('formHandler', $mdef)) {

				$methodCallName .= $this->_formHandlerSuffix;
			}

			if (!$mdef) {

				throw new Exception(
					"Call to undefined method: $method on action $action"
				);
			}

			$this->_doAroundCalls($mdef['before'], $cdata);

			$resultArray = array(
				'type'=>'rpc',
				'tid'=>$cdata->tid,
				'action'=>$action,
				'method'=>$method
			);

			//require_once("classes/$action.php");

			$actionClass = $this->_servicePackage.'_'.$action;

			$actionClassObject = new $actionClass();

			$params = isset($cdata->data) && is_array($cdata->data)
						? $cdata->data : array();

			try {

				$resultArray['result'] = call_user_func_array(
					array($actionClassObject, $methodCallName), $params
				);

			} catch (Exception $exception) {

				if ($exception->getCode() == 0) {

					throw $exception; // re-throw

				} else {

					// handle specific exceptions (auth, perm)

				}
			}

			$this->_doAroundCalls($mdef['after'], $cdata, $resultArray);
			$this->_doAroundCalls($apiAction['after'], $cdata, $resultArray);

		} catch(Exception $exception) {

			$resultArray['type'] = 'exception';
			$resultArray['message'] = $exception->getMessage();
			$resultArray['code'] = $exception->getCode();
			$resultArray['where'] = $exception->getTraceAsString();
		}
		return $resultArray;
	}

	/**
	 * doAroundCalls
	 *
	 * @param string|array $fns		Function name or array of function names
	 * @param StdClass $cdata	Method parameters
	 * @param array &$returnData		A variable to put return data into
	 *
	 */
	protected function _doAroundCalls(&$fns, &$cdata, &$returnData=null)
	{

		if (!$fns) {
			return;
		}

		if (is_array($fns)) {

			foreach ($fns as /** @var string $functionName */ $functionName) {

				$functionName($cdata, $returnData);
			}

		} else {
			$fns($cdata, $returnData);
		}
	}

	/**
	 * Main routing method
	 *
	 * Parses post data and calls the service methods.
	 * Prints result (json) directly to STDOUT.
	 *
	 */
	public function route()
	{

		$this->_buildApi();

		$isForm		= false;
		$isUpload	= false;
		$rawPostData = file_get_contents("php://input");

		if (isset($_POST['extAction'])) { // form post

			$isForm = true;
			$isUpload = @$_POST['extUpload'] == 'true';
			$data = new stdClass();
			$data->action = $_POST['extAction'];
			$data->method = $_POST['extMethod'];
			// not set for upload:
		    $data->tid = isset($_POST['extTID']) ? $_POST['extTID'] : null;
			$data->data = array($_POST, $_FILES);

		} else if (@$rawPostData) {

			header('Content-Type: text/javascript');
			$data = json_decode($rawPostData);
		} else {

			die('Invalid request.');
		}

		$response = null;
		if (is_array($data)) {
			$response = array();
			foreach ($data as $d) {
				$response[] = $this->_doRpc($d);
			}
		} else {
			$response = $this->_doRpc($data);
		}
		if ($isForm && $isUpload) {
			echo '<html><body><textarea>';
			echo json_encode($response);
			echo '</textarea></body></html>';
		} else {
			echo json_encode($response);
		}

	}

	/**
	 * Builds API definiton by using reflection the PHP service classes
	 *
	 * Modifies/Builds the protected $API hash of this class.
	 *
	 */
	protected function _buildApi()
	{

        $actArray = array();

		foreach (new DirectoryIterator(
			PATH_CORE.'/'.$this->_servicePath)
            as /** @var DirectoryIterator $fileInfo */ $fileInfo
		) {

    		if ($fileInfo->isDot()) continue;
    		if ($fileInfo->isDir()) continue;

    		$serviceName = $fileInfo->getBasename('.php');
    		$className = $this->_servicePackage.'_'.$serviceName;

			$ref = new ReflectionClass($className);

			$methodList = array();

			foreach (array_values($ref->getMethods()) as $method) {


				// skip non-public methods
				if (!$method->isPublic()) {

					continue;
				}


				$formHandler = false;
				$methodName = $method->name;

				// skip constructors and magic methods
				if (preg_match("#^_#", $methodName)) {

					continue;
				}

				if (
					preg_match(
						"#^(.+)".$this->_formHandlerSuffix."$#",
						$methodName, $match
					)
				) {
					$methodName = $match[1];
					$formHandler = true;
				}

				$methodInfo = array(
					'len'	=> 1
				);

				if ($formHandler) {
					$methodInfo['formHandler'] = true;
				}

				$methodList[$methodName] = $methodInfo;
			}

			$actArray[$serviceName]['methods'] = $methodList;
		}

		$this->_api = $actArray;
	}

	/**
	 * Prints the ExtJS API directly to STDOUT
	 *
	 * @uses Ext::buildAPI() to build the API
	 */
	public function getAPI()
	{

		header('Content-Type: 	application/json; charset=UTF-8');

		$this->_buildApi();

		// convert API config to Ext.Direct spec
		$actions = array();
		foreach ($this->_api as $aname=>&$apiAction) {
			$methods = array();
			foreach ($apiAction['methods'] as $mname=>&$m) {
				$md = array(
					'name'=>$mname,
					'len'=>$m['len']
				);
				if (isset($m['formHandler']) && $m['formHandler']) {
					$md['formHandler'] = true;
				}
				$methods[] = $md;
			}
			$actions[$aname] = $methods;
		}

		$cfg = array(
		    'url'=>$this->_routerUrl,
		    'type'=>'remoting',
			'actions'=>$actions
		);

		echo "Ext.ns('".$this->_namespace."');".
				$this->_namespace.".REMOTING_API = ";

		echo json_encode($cfg);
		echo ';';
	}
}
