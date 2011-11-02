<?php

namespace Processus\Lib\Db
{
    use Processus\Registry;
    use Processus\Interfaces\InterfaceDatabase;
    use Zend\Db\Db;
    use Zend\Db\Statement\Pdo;

    /**
     *
     */
    class MySQL implements InterfaceDatabase
    {

        /**
         * @var
         */
        private static $_instance;

        /**
         * @var
         */
        public $dbh;

        // #########################################################

        /**
         * @static
         * @return Core_Lib_Db_MySQL
         */
        public static function getInstance()
        {
            if (self::$_instance instanceof self !== TRUE) {
                self::$_instance = new self();
                self::$_instance->init();
            }
            
            return self::$_instance;
        }

        // #########################################################
        
        /**
         * @return void
         */
        public function init()
        {
            $registry = Registry::getInstance();
            $this->dbh = Db::factory($registry->getConfig('database')->adapter, $registry->getConfig('database')->params->toArray());
        }

        // #########################################################        

        /**
         * @param null $sql
         * @param array $args
         * @return Zend_Db_Statement_Pdo
         */
        private function _prepare($sql = NULL, $args = array())
        {
            $stmt = new Pdo($this->dbh, $sql);
            $stmt->setFetchMode(DB::FETCH_OBJ);
            $stmt->execute($args);
            return $stmt;
        }

        // #########################################################
        
        /**
         * @param null $sql
         * @param array $args
         * @return string
         */
        public function fetchValue($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args)->fetchColumn();
        }

        // #########################################################
        
        /**
         * @param null $sql
         * @param array $args
         * @return Zend_Db_Statement_Pdo
         */
        public function fetch($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args);
        }

        // #########################################################
        
        /**
         * @param null $sql
         * @param array $args
         * @return mixed
         */
        public function fetchOne($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args)->fetchObject();
        }

        // #########################################################
        
        /**
         * @param null $sql
         * @param array $args
         * @return array
         */
        public function fetchAll($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args)->fetchAll();
        }

        // #########################################################
        

        /**
         * @param null $tableName
         * @param array $values
         * @return
         */
        public function insert($tableName = NULL, $values = array())
        {
            if (! is_null($tableName) && ! empty($values)) {
                // add an ID if not existing
                if (! array_key_exists('id', $values)) {
                    $values['id'] = NULL;
                }
                
                // prepare placeholders and values
                $_set = array();
                $_placeholder = array();
                $_values = array();
                
                foreach ($values as $key => $val) {
                    $_set[] = $key;
                    
                    $placeholder_key = ':' . $key;
                    $_placeholder[] = $placeholder_key;
                    
                    $_values[$placeholder_key] = $val;
                }
                
                // build sql
                $sql = 'INSERT INTO ' . $tableName . ' (' . join(',', $_set) . ') VALUES (' . join(',', $_placeholder) . ')';
                
                // insert
                $this->_prepare($sql, $_values);
            }
            
            return;
        }

        // #########################################################
        

        /**
         * @param null $tableName
         * @param array $values
         * @param array $conditions
         * @return
         */
        public function update($tableName = NULL, $values = array(), $conditions = array())
        {
            if (! is_null($tableName) && ! empty($values) && array_key_exists('id', $conditions)) {
                // prepare placeholders and values
                $_set = array();
                $_values = array();
                
                foreach ($values as $key => $val) {
                    $placeholder_key = ':' . $key;
                    $_set[] = $key . '=' . $placeholder_key;
                    $_values[$placeholder_key] = $val;
                }
                
                // prepare conditions
                $_cond = array();
                
                foreach ($conditions as $key => $val) {
                    $placeholder_key = ':_' . $key;
                    $_cond[] = $key . '=' . $placeholder_key;
                    $_values[$placeholder_key] = $val;
                }
                
                // build sql
                $sql = 'UPDATE ' . $tableName . ' SET ' . join(',', $_set) . ' WHERE ' . join(' AND ', $_cond);
                
                // update
                $this->_prepare($sql, $_values);
            }
            
            return;
        }
    }
}

?>