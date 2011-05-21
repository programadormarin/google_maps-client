<?php
namespace entities 
{
	class Sistema 
	{
		/**
		 * @var int
		 */
		private $id;
		/**
		 * @var string
		 */
		private $nome;
		/**
		 * @var string
		 */
		private $key;
		
		/**
		 * @param int $newId
		 */
		public function setId($newId)
		{
			$this->id = $newId;
		}
		
		/**
		 * @param string $newNome
		 */
		public function setNome($newNome)
		{
			$this->nome = $newNome;
		}
		
		/**
		 * @param string $newKey
		 */
		public function setKey($newKey)
		{
			$this->key = $newKey;
		}
		
		/**
		 * @return number
		 */
		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return string
		 */
		public function getNome()
		{
			return $this->nome;
		}
		
		/**
		 * @return string
		 */
		public function getKey()
		{
			return $this->key;
		}
	}
}