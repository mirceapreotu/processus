<?php
/**
 * Lib_Solr_Select
 *
 * This class tries to mimic Zend_Db_Select
 *
 * @category	meetidaaa.com
 * @package		Lib_Solr
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Solr_Select
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
class Lib_Solr_Select
{

    const FETCH_TYPE_SEARCH = 1;
    const FETCH_TYPE_MLT = 2;

    /**
     * @var Lib_Solr_Client
     */
    protected $_solrClient = null;

    /**
     * @var int
     */
    protected $_fetchType = self::FETCH_TYPE_SEARCH;

    /**
     * @var string
     */
    protected $_searchstring = '';

	/**
	 * Solr default search parameters
	 *
	 * @var array
	 */
	protected $_params = array(
		'qt'		=> 'dismax', // default
        'fl'        => 'id,score',
        'sort'      => 'score desc'
	);

	/**
	 * Max. number of documents to return
	 *
	 * @var int
	 */
	protected $_limit = 200; // sensible default

    /**
     * Max. number of documents to return
     *
     * @var int
     */
    protected $_offset = 0; // start with first document

	/**
	 * Boost Function
	 *
	 * @var string
	 */
	protected $_boostFunction = '';

    /**
     * @return Lib_Solr_Select
     */
    public function reset()
    {
        $this->_params = array(
            'qt'		=> 'dismax' // default
        );
        return $this;
    }

	/**
	 * setLimit - how many documents to return (max)
     * @param int $limit
     * @return Lib_Solr_Select
	 */
	public function limit($limit, $offset = null)
	{
		$this->_limit = $limit;
        if ($offset !== null) {
            $this->_offset = $offset;
        }
        return $this;
	}

    /**
     * How any docs to retrieve
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * setOffset - where to star (0 == start)
     *
     * @param int $offset
     * @return Lib_Solr_Select
     */
    public function offset($offset)
    {
        $this->_offset = $offset;
        return $this;
    }

    /**
     * getOffset - where to start (0 == start)
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

	/**
	 * order
	 *
	 * @param string $field
	 * @param string $order	asc or desc
     * @return Lib_Solr_Select
	 */
	public function order($field, $order='asc')
	{
		$this->_params['sort'] = $field.' '.$order;
        return $this;
	}

    /**
     * @param string $specification 1-n: min matches, or percent: "30%"
     * @return Lib_Solr_Select
     */
    public function minimumMatch($specification='1')
    {
        $this->_params['mm'] = $specification;
        return $this;
    }

    /**
     * MoreLikeThis
     *
     * @param string|array $fields
     * @return Lib_Solr_Select
     */
    public function mlt($fields)
    {
        if (is_array($fields)) {
            $fields = join(',', $fields);
        }
        $this->_fetchType = self::FETCH_TYPE_MLT;
        $this->_params['mlt.fl'] = $fields;

        $this->_params['mlt.mintf'] = 1;
        $this->_params['mlt.mindf'] = 1;
        $this->_params['mlt.boost'] = 'true';

        return $this;
    }

	/**
	 * filter
	 *
	 * Example: "category_type:MUSIC"
	 *
	 * @param string $filterQuery
	 * @param string $order	asc or desc
     * @return Lib_Solr_Select
	 */
	public function filter($filterQuery)
	{
		$this->_params['fq'] = $filterQuery;

        return $this;
	}

	/**
	 * handler
	 *
	 * @param string $queryHandler
     * @return Lib_Solr_Select
	 */
	public function handler($queryHandler='dismax')
	{
		$this->_params['qt'] = $queryHandler;
        return $this;
	}

	/**
	 * setSearchFields - which fields to search
	 *
	 * @param string|array $queryFields
     * @return Lib_Solr_Select
	 */
	public function search($searchstring, $queryFields=array())
	{

        $this->_searchstring = $searchstring;

		if (is_array($queryFields) && count($queryFields) > 0) {

			$queryFields = join(' ', $queryFields);
            $this->_params['qf'] = $queryFields;

		    // automatically set phrase boost

            if (!array_key_exists('pf', $this->_params)) {

                $this->phraseboost($queryFields);
            }
		}

        return $this;
	}

	/**
	 * setFieldList - which fields to return
	 *
	 * @param string|array $queryFields
     * @return Lib_Solr_Select
	 */
	public function output($fieldList)
	{
		if (is_array($fieldList)) {

			$fieldList = join(',', $fieldList);
		}
		$this->_params['fl'] = $fieldList;

        return $this;
	}

	/**
	 * setPhraseBoost
	 *
	 * @param string|array $phraseBoost
     * @return Lib_Solr_Select
	 */
	public function phraseboost($phraseBoost)
	{
		if (is_array($phraseBoost)) {

			$phraseBoost = join(' ', $phraseBoost);
		}
		$this->_params['pf'] = $phraseBoost;

        return $this;
	}

	/**
	 * setBoostFunction
	 *
	 * @param string|array $boostFunction
     * @return Lib_Solr_Select
	 */
	public function boostfunction($boostFunction)
	{
		if (is_array($boostFunction)) {

			$boostFunction = join(' ', $boostFunction);
		}
		$this->_params['bf'] = $boostFunction;

        return $this;
	}

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->_searchstring;
    }

    /**
     * @return Lib_Solr_Result
     */
    public function fetch()
    {

        if ($this->_fetchType == self::FETCH_TYPE_SEARCH) {

            $result = $this->_solrClient->search(
                $this
            );

        } else {

            $result = $this->_solrClient->moreLikeThis(
                $this
            );
        }

        $resultObject = new Lib_Solr_Result($result);

		return $resultObject;
	}

    /**
     * @param Lib_Solr_Client $solrClient
     * @return void
     */
    public function setClient(Lib_Solr_Client $solrClient)
    {
        $this->_solrClient = $solrClient;
    }

    /**
     * @return Lib_Solr_Client|null
     */
    public function getClient()
    {
        return $this->_solrClient;
    }
}
