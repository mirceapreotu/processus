<?php

    namespace Processus
    {

        /**
         *
         */
        class Application
        {

            /**
             * @var Application
             */
            private static $_instance;

            // #########################################################


            /**
             * @return \Processus\Application
             */
            public static function getInstance()
            {
                if (self::$_instance instanceof self !== TRUE) {
                    self::$_instance = new Application();
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
            }

            // #########################################################
            
            public function getUserBo()
            {
                
            }

        }
    }

?>