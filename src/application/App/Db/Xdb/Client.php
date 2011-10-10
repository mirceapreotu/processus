<?php
/**
 * App_Db_Xdb_Client
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Db_Xdb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Db_Xdb_Client
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Db_Xdb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */


class App_Db_Xdb_Client extends Lib_Db_Xdb_Client
{

    /**
     * @var int
     */
	private static $_masterRequestCount = 0;

    /**
     * @var Zend_Db_Adapter_Pdo_Mysql
     */
    protected $_zendClient;

    /**
     * @var Zend_Db_Adapter_Pdo_Mysql
     */
    protected $_zendClientSlave;


     /**
     * @var Zend_Config
     */
    protected $_config;


    /**
     * override in subclass or inject using Zend_Config
     * @var array
     */
    protected $_configDefault = array(


        'master' => array(
            'adapter' => 'pdo_mysql',
            'params'  => array(
                'host'     => 'DB_HOST',
                // username 16chars
                'username' => 'DB_USERNAME',
                'password' => 'DB_PASS',
                'dbname'   => 'DB_NAME',
                'driver_options'  => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''
                )
            )
        ),

        'slaves' => array(
            // a db slave
            /*
            array(
                'adapter' => 'pdo_mysql',
                'params'  => array(
                    'host'     => 'DB_HOST_SLAVE',
                    // username 16chars
                    'username' => 'DB_USERNAME_SLAVE',
                    'password' => 'DB_PASS_SLAVE',
                    'dbname'   => 'DB_NAME_SLAVE',
                    'driver_options'  => array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''
                    )
                )
            ),
            */
          // another slave
        ),

        
    );


    /**
     * @return Zend_Config
     */
    public function getConfig()
    {
        if (($this->_config instanceof Zend_Config) !== true) {
            $this->_parseConfig();
            if ($this->_config instanceof Zend_Config) {
                $this->_onConfigParsed();
            } else {
                throw new Exception(
                    "Method returns invalid result at ".__METHOD__
                );
            }
        }
        return $this->_config;
    }

    /**
     * @return Zend_Config
     */
    public function getConfigDefault()
    {
        return new Zend_Config((array)$this->_configDefault, true);
    }

    protected function _parseConfig()
    {
        $configDefault = $this->getConfigDefault();
        $zendConfig = Lib_Utils_Config_Parser::loadByClass($this);
        /*
        $zendConfig = Lib_Utils_Config_Parser::loadByClassOrSuperClasses(
            $this
        );
        */
        $config = Lib_Utils_Config_Parser::merge(
            array(
                 $configDefault,
                 $zendConfig
            )
        );

        $this->_config = $config;


    }


    /**
     * your hooks here
     * @return void
     */
    protected function _onConfigParsed()
    {
        $config = $this->getConfig();

    }








	/**
     * override
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */
	public function getZendClient()
	{
        if (($this->_zendClient instanceof Zend_Db_Adapter_Pdo_Mysql)!==true) {

            $config = $this->getConfig();



            if (($config instanceof Zend_Config)!=true) {
                throw new Exception(
                    "Invalid config at "
                    . __METHOD__
                    . " for class "
                    . get_class($this));
            }
            $master = $config->master;

            if (($master instanceof Zend_Config)!=true) {
                throw new Exception(
                    "Invalid config.master at "
                    . __METHOD__
                    . " for class "
                    . get_class($this));
            }
            /**
             * @var Zend_Config $adapter
             */
            $adapter = $master->adapter;
            if (is_string($adapter)!=true) {
                throw new Exception(
                    "Invalid config.master.adapter at "
                    . __METHOD__
                    . " for class "
                    . get_class($this));
            }
            /**
             * @var Zend_Config $params
             */
            $params = $master->params;
            if (($params instanceof Zend_Config)!=true) {
                throw new Exception(
                    "Invalid config.master.params at "
                    . __METHOD__
                    . " for class "
                    . get_class($this));
            }


            $this->_zendClient = Zend_Db::factory($adapter, $params->toArray());


            // slaves
            // setup a random slave, if defined:

        if (isset($config->databaseSlaves)) {

            $slaveList = $config->databaseSlaves->toArray();
            $slaveIndex = (int)rand(0, (count($slaveList)-1));

            $slaveConfig = $slaveList[$slaveIndex];

            $dbSlave = Zend_Db::factory(
                $slaveConfig['adapter'],
                $slaveConfig['params']
            );

            Zend_Registry::set('DBSLAVE', $dbSlave);
        }



        }
        return $this->_zendClient;

	}


    /**
     * override
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */
	public function getZendClientMaster()
	{
        return $this->getZendClient();
    }


    /**
     *
	 * @return Zend_Db_Adapter_Pdo_Mysql|null
	 */
	public function getZendClientSlave()
	{

        if (
                ($this->_zendClientSlave instanceof Zend_Db_Adapter_Pdo_Mysql)
                !==true) {

            $this->_zendClientSlave =null;

            $config = $this->getConfig();

            if (($config instanceof Zend_Config)!=true) {
                throw new Exception(
                    "Invalid config at "
                    . __METHOD__
                    . " for class "
                    . get_class($this));
            }

            if (isset($config->slaves)!==true) {

               return $this->_zendClientSlave;
            }

            /**
             * @var Zend_Config $slaveList
             */
            $slaveList = $config->slaves;

            if (($slaveList instanceof Zend_Config)!==true) {
                return $this->_zendClientSlave;
            }

            $slaveList = $slaveList->toArray();
            if (count($slaveList)<1) {

                return $this->_zendClientSlave;
            }

            $slaveIndex = (int)rand(0, (count($slaveList)-1));
            $slaveConfig = $slaveList[$slaveIndex];

            $this->_zendClientSlave = Zend_Db::factory(
                $slaveConfig['adapter'],
                $slaveConfig['params']
            );


        }
        return $this->_zendClientSlave;

	}


    /**
     * override
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */
	public function getZendClientFromPool()
	{
		if ($this->isMaster()) {

			return $this->getZendClientMaster();

		} else {

            $slave = $this->getZendClientSlave();
            if ($slave instanceof Zend_Db_Adapter_Pdo_Mysql) {

                return $slave;
            } else {
                
                return $this->getZendClientMaster();
            }

		}
	}






    /**
     * @return void
     */
    public function init()
    {

    }


}
