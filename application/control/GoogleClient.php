<?php
namespace control
{
	use entities\Pesquisa;
	use dao\PesquisaDao;
	use config\HttpRequest;
	use config\AutenticacaoException;
	use dao\DaoException;
	use config\Autenticacao;

	class GoogleClient
	{
		private $pesquisa;
		private $resposta;
		private $nome;
		private $chave;
		/**
		 * @return \config\stdClass
		 */
		private function autenticaUsuarioSenha()
		{
			try {
				return Autenticacao::getInstance()->validaSistema();
			} catch (AutenticacaoException $e) {
				header("Status: 401");
				throw new \Exception('Erro na autenticação dos parâmetros:<br/><pre>' . $e->getTraceAsString() . '</pre>');
			} catch (DaoException $e) {
				throw new \Exception('Erro na busca no bando de dados:<br/><pre>' . $e->getTraceAsString() . '</pre>');
				header("Status: 401");
			} catch (\Exception $e) {
				header("Status: 401");
				throw new \Exception('Erro:<br/><pre>' . $e->getTraceAsString() . '</pre>');
			}
		}
		
		public function __construct()
		{
			try {
				$request = $this->autenticaUsuarioSenha();
			} catch (\Exception $e) {
				throw new \Exception('Não foi possível autenticar o seu nome e key. Tente novamente mais tarde!<br/>' . $e->getMessage());
			}

			$pesquisaDao = new PesquisaDao();
			try {
				$pesquisa = $pesquisaDao->load($request->pesquisa);
				$pesquisa->setUltimaRequisicao($this->getDateTime());
				$pesquisa->setNumeroDeRequisicao(($pesquisa->getNumeroDeRequisicao() + 1));
				$pesquisaDao->save($pesquisa);
				echo $pesquisa->getResposta();
				return;
			} catch (DaoException $e) {
				$request->resposta = $this->requisitaGoogle($request->pesquisa);
			}
			
			$pesquisa = $this->montaPesquisa($request);
			
			try {
				$pesquisaDao->save($pesquisa);
			} catch (DaoException $e) {
				throw new \Exception('Não foi possível salvar sua pesquisa no nosso banco de dados. ' . $e->getMessage() . '<pre>' . $e->getTraceAsString());
			} catch (\Exception $e) {
				throw new \Exception('Não foi possível salvar sua pesquisa.');
			}
			
			echo $pesquisa->getResposta();
		}
		
		private function requisitaGoogle($pesquisa)
		{
			$retorno = new HttpRequest('http://www.google.com/maps/geo', 'GET', array('q' => $pesquisa, 'output' => 'json'));
			$retorno = json_decode($retorno->load());
			$retorno = json_encode(
				array(
					'lat' => $retorno->Placemark[0]->Point->coordinates[0],
					'long' => $retorno->Placemark[0]->Point->coordinates[1]
				)
			);
			return $retorno;
		}
		
		private function montaPesquisa($request)
		{
			$pesquisa = new Pesquisa();
			$pesquisa->setNumeroDeRequisicao(1);
			$pesquisa->setPesquisa($request->pesquisa);
			$pesquisa->setResposta($request->resposta);
			$pesquisa->setSistema($request->sistema);
			$pesquisa->setUltimaRequisicao($this->getDateTime());
			return $pesquisa;
		}
		
		private function getDateTime()
		{
			return new \DateTime();
		}
	}
}