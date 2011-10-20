<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:44
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Connection
{

    // ++++++++++++++++ working with redis protocol +++++++++++
    const REDIS_PROTOCOL_CRLF = "\r\n";

    // ++++++++++++++++ working with socket ++++++++++++++++++++
    /**
    * @var null|string
    */
    protected $_host ='localhost';
    /**
    * @var null|string|int
    */
    protected $_port ='6379';

    /**
     * @var bool
     */
    protected $_isPersistent = false;

    /**
     * @var resource|null
     */
    protected $_socket;







    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    

    /** Close connection */
    public function __destruct()
    {
        try {
            $this->socketClose();
        } catch(Exception $e) {
            // NOP, just shut up
        }

    }


    // ++++++++++++++++++ working with config +++++++++++++++++++
    public function applyConfig(array $config)
    {
        if (Redisek_Util_Array::hasProperty($config, "host")) {
            $configHost = Redisek_Util_Array::getProperty(
                $config, "host"
            );
            $this->setHost($configHost);
        }
        if (Redisek_Util_Array::hasProperty($config, "port")) {
            $configPort = Redisek_Util_Array::getProperty(
                $config, "port"
            );
            $this->setPort($configPort);
        }
        if (Redisek_Util_Array::hasProperty($config, "isPersistent")) {
            $configIsPersistent = Redisek_Util_Array::getProperty(
                $config, "isPersistent"
            );
            $this->setIsPersistent($configIsPersistent);
        }


    }



    // ++++++++++++++++ working with socket ++++++++++++++++++++

    /**
     * @return bool
     */
    public function isPersistent()
    {
        return (bool)($this->_isPersistent===true);
    }

    /**
     * @param  bool $value
     * @return void
     */
    public function setIsPersistent($value)
    {
        $this->_isPersistent=($value===true);
    }

    public function setHost($value)
    {
        if ($this->_host === $value) {
            // nothing changed
            return;
        }
        $this->_host = $value;
        $this->socketClose();
    }

    /**
     * @return null|string
     */
    public function getHost()
    {
        return $this->_host;
    }
    public function setPort($value)
    {
        if ($this->_port === $value) {
            // nothing changed
            return;
        }
        $this->_port = $value;
        $this->socketClose();
    }
    /**
     * @return int|null|string
     */
    public function getPort()
    {
        return $this->_port;
    }

    /**
     * @throws Redisek_Exception
     * @return void
     */

    public function socketConnect()
    {
        $errorNo = 0;
        $errorMessage = null;
        $socket = fsockopen(
            $this->getHost(),
            $this->getPort(),
            $errorNo, $errorMessage
        );
        $this->_socket = $socket;

        if (!is_resource($this->_socket)) {

            $e = new Redisek_Exception(
                "Error while trying to connect to"
                ." host=".$this->getHost().":".$this->getPort()
                ." message=".$errorMessage
            );
            $e->setServer($this->getHost(), $this->getPort());
            $e->setType(Redisek_Exception::ERROR_REDIS_CONNECTION_FAILED);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                    "errorNo" => $errorNo,
                    "errorMessage" => $errorMessage,
                ));
            throw $e;
        }
    }

    /**
     * @return void
     */
    public function socketClose()
    {
        if (is_resource($this->_socket)) {
            fclose($this->_socket);
        }
    }


    /**
     * @return null|resource
     */
    public function getSocketConnection()
    {
        if (is_resource($this->_socket)) {
            return $this->_socket;
        }

        $this->socketConnect();
        return $this->_socket;

    }




    // ++++++++++++++++ working with redis protocol +++++++++++

    /**
     * @param  string $method
     * @param array $params
     * @return array|int|null|string
     */
    public function request($method, array $params)
    {
        return $this->_request($method, $params);
    }

    /**
     * @throws Redisek_Exception
     * @param  string $method
     * @param array $params
     * @return array|int|null|string
     */
	protected function _request($method, array $params)
	{
        $args = array(
            $method
        );
        foreach($params as $param) {
            $args[] = $param;
        }
        $args = (array)$args;

        $crlf = self::REDIS_PROTOCOL_CRLF;
		$command = '*'.count($args).$crlf;
		foreach ($args as $arg) {

            $isValidType = (is_string($arg)||(is_int($arg))||(is_float($arg)));
            if (is_bool($arg)) {
                $isValidType = false;
            }

            if (!$isValidType) {
                $e = new Redisek_Exception(
                "Invalid argument type (string/int/float expected)."
                ." Error while trying to send request to "
                ." host=".$this->getHost().":".$this->getPort()
            );
                $e->setServer($this->getHost(), $this->getPort());
                $e->setType(Redisek_Exception::ERROR_REDIS_REQUEST_FAILED);
                $e->createFault(
                    $this,
                    __METHOD__,
                    array(
                    ));
                throw $e;
            }

            $arg = (string)$arg;
            $command .= "$".strlen($arg).$crlf.
                        $arg.$crlf
            ;
        }

        $socket = $this->getSocketConnection();

		$bytes = fwrite($socket, $command);
		if (($bytes === false)||($bytes<1)) {

            if ($this->isPersistent() !== true) {
                $this->socketClose();
            }

            $e = new Redisek_Exception(
                "Error while trying to send request to "
                ." host=".$this->getHost().":".$this->getPort()
            );
            $e->setServer($this->getHost(), $this->getPort());
            $e->setType(Redisek_Exception::ERROR_REDIS_REQUEST_FAILED);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                ));
            throw $e;
        }

        return $this->_processResponse($socket);

	}


    /**
     * @throws Redisek_Exception
     * @param  resource $socket
     * @return array|int|null|string
     */
	protected function _processResponse($socket)
	{
        $response = null;

		$reply = trim(fgets($socket));
        if ($reply === false) {
            if (!$this->isPersistent()) {
                $this->socketClose();
            }

            $e = new Redisek_Exception(
                "Error while trying to process response from"
                ." host=".$this->getHost().":".$this->getPort()
            );
            $e->setServer($this->getHost(), $this->getPort());
            $e->setType(Redisek_Exception::ERROR_REDIS_RESPONSE_INVALID);
            $e->createFault(
                $this,
                __METHOD__,
                array(
                    "errorMessage" => "no reply",
                ));
            throw $e;
        }



		/**
		 * Thanks to Justin Poliey for original code of parsing the answer
		 * https://github.com/jdp
		 * Error was fixed there: https://github.com/jamm/redisent
		 */
		switch (substr($reply, 0, 1))
		{
			/* Error reply */
			case '-':
                if (!$this->isPersistent()) {
                    $this->socketClose();
                }

                $e = new Redisek_Exception(
                    "Error while trying to process response from"
                    ." host=".$this->getHost().":".$this->getPort()
                    ." ERROR: ".$reply
                );
                $e->setServer($this->getHost(), $this->getPort());
                $e->setType(Redisek_Exception::ERROR_REDIS_RESPONSE_INVALID);
                $e->createFault(
                    $this,
                    __METHOD__,
                    array(
                        "errorMessage" => "".$reply,
                    ));
                throw $e;

				break;

			/* Inline reply */
			case '+':

				return substr(trim($reply), 1);
				break;

			/* Bulk reply */
			case '$':
				$response = null;
				if ($reply=='$-1') {
                    return null;
                }
				$read = 0;
				$size = intval(substr($reply, 1));
				$chi = 0;
				if ($size > 0)
				{
					do
					{
						$chi++;
						$block_size = $size-$read;
						if ($block_size > 1024) {
                            $block_size = 1024;
                        }
						if ($block_size < 1) {
                            break;
                        }
						if ($chi > 1000) {
                            if (!$this->isPersistent()) {
                                $this->socketClose();
                            }
                            $e = new Redisek_Exception(
                                "Error while trying to process response from"
                                ." host=".$this->getHost().":".$this->getPort()
                                ." LOOP DETECTED"
                            );
                            $e->setServer($this->getHost(), $this->getPort());
                            $e->setType(
                                Redisek_Exception::ERROR_REDIS_RESPONSE_INVALID
                            );
                            $e->createFault(
                                $this,
                                __METHOD__,
                                array(
                                    "errorMessage" => "LOOP DETECTED: ".$reply,
                                ));
                            throw $e;
                        }
						$response .= fread($socket, $block_size);
						$read += $block_size;
					} while ($read < $size);
				}
				fread($socket, 2); /* discard crlf */

				break;

			/* Multi-bulk reply */
			case '*':
				$count = substr($reply, 1);
				if ($count=='-1') {
                    return null;
                }
				$response = array();
				for ($i = 0; $i < $count; $i++)
				{
					$response[] = $this->_processResponse($socket);
				}
				break;

			/* Integer reply */
			case ':':
				return intval(substr(trim($reply), 1));
				break;
			default:

                if ($this->isPersistent() !== true) {
                    $this->socketClose();
                }
                $e = new Redisek_Exception(
                    "Error while trying to process response from"
                    ." host=".$this->getHost().":".$this->getPort()
                    ." UNKNOWN RESPONSE TYPE"
                );
                $e->setServer($this->getHost(), $this->getPort());
                $e->setType(Redisek_Exception::ERROR_REDIS_RESPONSE_INVALID);
                $e->createFault(
                    $this,
                    __METHOD__,
                    array(
                        "errorMessage" => "UNKNOWN RESPONSE TYPE: ".$reply,
                    ));
                throw $e;


				break;
		}

		return $response;
	}







}
