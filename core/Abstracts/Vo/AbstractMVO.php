<?php

namespace Processus\Abstracts\Vo {
	
	/**
	 * User: francis
	 * Date: 7/29/11
	 * Time: 12:39 PM
	 * To change this template use File | Settings | File Templates.
	 */
	use Processus\Interfaces\InterfaceDto;
	
	use Processus\ProcessusRegistry;
	
	use Processus\Application;
	
	use Processus\Lib\Db\Memcached;
	
	use Processus\Lib\Server\ServerFactory;
	
	abstract class AbstractMVO extends AbstractVO {
		
		/**
		 * @var string
		 */
		protected $_memId;
		
		/** 
		 * @var string 
		 */
		protected $_saltValue;
		
		/** 
		 * @var string 
		 */
		protected $_hashAlgo;
		
		/** 
		 * @var Memcached 
		 */
		protected $_memcachedClient;
		
		/**
		 * @var mixed
		 */
		protected $_memDataProvider;
		
		/**
		 * @return string
		 */
		protected function getSaltValue() {
			return $this->_saltValue;
		}
		
		/**
		 * @return string
		 */
		protected function getHashAlgo() {
			return $this->_hashAlgo;
		}
		
		/**
		 * @return string
		 */
		protected function getMemId() {
			return $this->_memId;
		}
		
		/**
		 * @param string $mId
		 * @return \Processus\Abstracts\Vo\AbstractMVO
		 */
		public function setMemId(string $mId) {
			$this->_memId = $mId;
			return $this;
		}
		
		/**
		 * @return bool
		 */
		public function saveInMem() {
			if (! $this->getMemId ()) {
				return false;
			}
			return $this->getMemcachedClient ()->insert ( $this->getMemId (), $this->getData (), 0 );
		}
		
		/**
		 * @return bool
		 */
		public function deleteFromMem() {
			$this->getMemcachedClient ()->delete ( $this->getMemId () );
			return true;
		}
		
		/**
		 * @return object
		 */
		public function getFromMem() {
			$this->_data = $this->getMemcachedClient ()->fetch ( $this->getMemId () );
			return $this->_data;
		}
		
		/**
		 * @throws Exception
		 * @return \Processus\Lib\Db\Memcached
		 */
		public function getMemcachedClient() {
			if (! $this->_memcachedClient) {
				try {
					
					$this->_memcachedClient = ServerFactory::memcachedFactory ( $this->getMembaseHost (), $this->getDataBucketPort () );
				
				} catch ( \Exception $error ) {
					
					throw $error;
				
				}
			}
			return $this->_memcachedClient;
		}
		
		/**
		 * @return string
		 */
		protected function getMembaseHost() {
			return "127.0.0.1";
		}
		
		/**
		 * @return string
		 */
		protected function getDataBucketPort() {
			return "11211";
		}
		
		/**
		 * @param InterfaceDto $dto
		 * @return InterfaceDto
		 */
		public function setDto(InterfaceDto $dto) {
			$dto->setData ( $this->getData () );
			return $dto;
		}
	}
}

?>