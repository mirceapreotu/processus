<?php

    namespace Processus
    {

        /**
         *
         */
        class Application
        {

            /**
             * @var App_Application
             */
            private static $_instance;

            // #########################################################


            /**
             * @static
             * @return App_Application
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
            }

            // #########################################################
            
            public function getUserBo()
            {
                
            }

        }
    }

?>