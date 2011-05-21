<?php
namespace config
{
	use entities\Sistema;
	use dao\SistemaDao;

	class Autenticacao 
	{
		private static $instance;
		
		private function __construct(){}
		
		public static function getInstance()
		{
			if (is_null(self::$instance)) {
				self::$instance = new Autenticacao();
			}
	
			return self::$instance;
		}
		
		protected function validaRequest()
		{
			if (!isset($_GET['nome'])) {
				throw new AutenticacaoException('Faltou passar o parâmetro "nome".');
			}
			if (!isset($_GET['key'])) {
				throw new AutenticacaoException('Faltou passar o parâmetro "key".');
			}
			if (!isset($_GET['endereco'])) {
				throw new AutenticacaoException('Faltou passar o parâmetro "endereco".');
			}
			
			$request = new \stdClass();
			$request->nome = $_GET['nome'];
			$request->key = $_GET['key'];
			$request->pesquisa = $_GET['endereco'];
			
			return $request;
		}
		
		/**
		 * @throws AutenticacaoException
		 * @return Sistema
		 */
		public function validaSistema()
		{
			$request = $this->validaRequest();
			try {
				$request->sistema = $this->getSistemaDao()->load($request->nome, $request->key);
			} catch (DaoException $e) {
				throw new AutenticacaoException('Usuário ou senha não incorretos.');
			}
			return $request;
		}
		
		/**
		 * @return \dao\SistemaDao
		 */
		protected function getSistemaDao()
		{
			return new SistemaDao();
		}
	}
}