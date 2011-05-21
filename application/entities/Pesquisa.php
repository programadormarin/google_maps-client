<?php
namespace entities 
{
	use entities\Sistema;
	class Pesquisa 
	{
		private $id;
		/**
		 * @var Sistema
		 */
		private $sistema;
		/**
		 * @var string
		 */
		private $pesquisa;
		/**
		 * @var string json
		 */
		private $resposta;
		/**
		 * @var DateTime
		 */
		private $ultimaRequisicao;
		/**
		 * @var int
		 */
		private $numeroDeRequisicoes;
		
		/**
		 * @param int $newId
		 */
		public function setId($newId)
		{
			$this->id = $newId;
		}
		
		/**
		 * @param Sistema $newSistema
		 */
		public function setSistema(Sistema $newSistema)
		{
			$this->sistema = $newSistema;
		}
		
		/**
		 * @param string $newPesquisa
		 */
		public function setPesquisa($newPesquisa)
		{
			$this->pesquisa = $newPesquisa;
		}
		
		public function setResposta($newResposta)
		{
			$this->resposta = $newResposta;
		}
		
		/**
		 * @param \DateTime $newUltimaRequisicao
		 */
		public function setUltimaRequisicao(\DateTime $newUltimaRequisicao)
		{
			$this->ultimaRequisicao = $newUltimaRequisicao;
		}
		
		/**
		 * @param int $newNumeroDeRequisicao
		 */
		public function setNumeroDeRequisicao($newNumeroDeRequisicao)
		{
			$this->numeroDeRequisicoes = $newNumeroDeRequisicao;
		}
		
		/**
		 * @return number
		 */
		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return Sistema
		 */
		public function getSistema()
		{
			return $this->sistema;
		}
		
		/**
		 * @return string
		 */
		public function getPesquisa()
		{
			return $this->pesquisa;
		}
		
		/**
		 * @return string
		 */
		public function getResposta()
		{
			return $this->resposta;
		}
		
		/**
		 * @return \DateTime
		 */
		public function getUltimaRequisicao()
		{
			return $this->ultimaRequisicao;
		}
		
		/**
		 * @return number
		 */
		public function getNumeroDeRequisicao()
		{
			return $this->numeroDeRequisicoes;
		}
	}
}