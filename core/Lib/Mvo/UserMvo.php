<?php

/** 
 * @author francis
 * 
 * 
 */

namespace Processus\Lib\Mvo {
	use Processus\Abstracts\Vo\AbstractMVO;
	
	class UserMvo extends AbstractMVO {
		
		/**
		 * @return string
		 */
		public function getFirstname() {
			return $this->getValueByKey ( "firstName" );
		}
		
		/**
		 * @return string
		 */
		public function getLastname() {
			return $this->getValueByKey ( "lastName" );
		}
		
		/**
		 * @return string
		 */
		public function getFullName() {
			return $this->getValueByKey ( "fullName" );
		}
	}
}
?>