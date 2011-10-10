<?php
/**
 * Lib_Solr_Client
 *
 * Base class for solr access.
 *
 * @category	meetidaaa.com
 * @package		Lib_Solr
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Solr_Client
 *
 * Base class for solr access.
 *
 * @category	meetidaaa.com
 * @package		Lib_Solr
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Solr_Client
{

	/**
	 * Solr handle
	 *
	 * @var Apache_Solr_Service
	 */
	protected $_solr;

    /**
     * @var bool
     */
    protected $_autocommitEnabled = true;

	/**
	 * Constructor
	 *
	 * @param array $config
	 */
	public function __construct($config=null)
	{
		if ($config !== null) {

			$this->init($config);
		}
	}

	/**
	 * Initialize Apache Solr class
	 *
	 * @param array $config
	 */
	public function init($config = null)
	{

        if ($config === null) {
            $config = array(
                'host' => 'localhost',
                'port' => 8983,
                'path' => '/solr/',
            );
        }

		$this->_solr = new Apache_Solr_Service();

		if (is_array($config)) {

			foreach ($config as $configKey => $configValue) {

				switch($configKey) {

					case 'host':
					case 'port':
					case 'path':
						$method = 'set'.strtoupper($configKey);
						$this->_solr->$method($configValue);
						break;
					default:
						throw new Exception(
							'Config for '.get_class($this).
							'::init() has an invalid key ['.$configKey.']'
						);
				}
			}

		} else {

			throw new Exception(
				'Config for '.get_class($this).'::init() is not an array.'
			);
		}
	}

	/**
	 * cleanSearchstring
     * Removes braces and special characters
	 *
	 * @param string $searchstring
	 * @return string
	 */
	public static function cleanSearchstring($searchstring)
	{

		$searchstring = str_replace(
			array(
				'+', '|', '&', '!', '(', ')', '{', '}',
				'[', ']', '^', '"', '~', '*', '?', ':', '\\'
			),
			' ',
			$searchstring
		);

		// compress spaces
		$searchstring = preg_replace(
			'/ +/', ' ', trim(strtolower($searchstring))
		);

		return $searchstring;
	}

	/**
	 * Add or update a document
	 *
	 * @param App_Solr_Document $document
	 * @return boolean true on success
	 */
	public function add($document)
	{

		$xmlWriter =new XMLWriter();
		$xmlWriter->openMemory();
		$xmlWriter->startDocument('1.0', 'UTF-8');
		$xmlWriter->startElement('add');

		$xmlWriter->writeRaw((string)$document);

		$xmlWriter->endElement();
		$xmlWriter->endDocument();

		$rawXml = $xmlWriter->flush();
		$res = $this->_solr->add($rawXml);

        if ($this->getAutocommitEnabled() === true) {
		    $this->commit();
        }

		return $res;
	}

    /**
     * @return void
     */
    public function commit()
    {
        $this->_solr->commit();
    }

    /**
     * @param string $query
     * @return void
     */
    public function purge($query = '*')
    {
        $this->_solr->deleteByQuery($query);
    }


    /**
     * @param bool $isAutocommitEnabled
     * @return void
     */
    public function setAutocommitEnabled($isAutocommitEnabled = true)
    {
        $this->_autocommitEnabled = $isAutocommitEnabled;
    }

    /**
     * @return bool
     */
    public function getAutocommitEnabled()
    {
        return $this->_autocommitEnabled;
    }

	/**
	 * Formats date/time for solr
	 *
	 * @param string $datetime A date/time string (strtotime)
	 * @return string A date/time string formatted for solr queries
	 */
	public static function getFormattedTime($datetime)
	{
		$timedata = strtotime($datetime);

		$datestring = date('Y-m-d', $timedata);
		$timestring = date('H:i:s', $timedata);

		$formatted = $datestring.'T'.$timestring.'.000Z';

		return $formatted;
	}

	/**
	 * Gets a date/time range query
	 *
	 * The resulting string ist to be used e.g. in a
	 * filter query condition.
	 *
	 * @param string $fromdate A date/time string (strtotime)
	 * @param string $todate A date/time string (strtotime)
	 *
	 * @return string A date/time range query string
	 */
	public static function getDateRangeQuery($field, $fromdate, $todate)
	{

		$fromFormatted	= self::getFormattedTime($fromdate);
		$toFormatted	= self::getFormattedTime($todate);

		$querystring = "$field:[$fromFormatted TO $toFormatted]";

		return $querystring;
	}

    /**
     * @return Lib_Solr_Select
     */
    public function select()
    {
        $select = new Lib_Solr_Select();
        $select->setClient($this);
        return $select;
    }

    /**
     * @param Lib_Solr_Select $select
     * @return Apache_Solr_Response
     */
    public function search(Lib_Solr_Select $select)
    {
        $searchstring = self::cleanSearchstring($select->getQuery());
        $result = $this->_solr->search(
            $searchstring,
            $select->getOffset(),
            $select->getLimit(),
            $select->getParams(),
            Apache_Solr_Service::METHOD_POST
        );

        return $result;
    }

    /**
     * @param Lib_Solr_Select $select
     * @return Apache_Solr_Response
     */
    public function moreLikeThis(Lib_Solr_Select $select)
    {
        $searchstring = $select->getQuery();
        $result = $this->_solr->mlt(
            $searchstring,
            $select->getOffset(),
            $select->getLimit(),
            $select->getParams(),
            Apache_Solr_Service::METHOD_GET
        );

        return $result;
    }
}
