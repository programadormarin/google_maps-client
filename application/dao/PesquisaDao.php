<?php
namespace dao
{
	use dao\ConexaoMysql;
	use entities\Pesquisa;
	use dao\Dao;
	
	class PesquisaDao extends Dao
	{
		public function save(Pesquisa $pesquisa)
		{
			$pdo = ConexaoMysql::getInstance();
			$update = 'UPDATE pesquisa SET sistema_id = ?, pesquisa = ?, resposta = ?, ultima_requisicao = ?, numero_requisicoes = ? WHERE id = ? ;';
			$insert = 'INSERT INTO pesquisa (sistema_id, pesquisa, resposta, ultima_requisicao, numero_requisicoes) VALUES(?, ?, ?, ?, ?)';
			$sql = ($pesquisa->getId()) ? $update :	$insert;
			$stm = $pdo->prepare($sql);
			$stm->bindValue(1, $pesquisa->getSistema()->getId());
			$stm->bindValue(2, $pesquisa->getPesquisa());
			$stm->bindValue(3, $pesquisa->getResposta());
			$stm->bindValue(4, $pesquisa->getUltimaRequisicao()->format('Y-m-d H:i:s'));
			$stm->bindValue(5, $pesquisa->getNumeroDeRequisicao());
			if ($pesquisa->getId()) $stm->bindValue(6, $pesquisa->getId());
			
			try {
				$stm->execute();
			} catch (Exception $e){
				throw new DaoException('Não foi possível alterar ou inserir dados no banco de dados.<br/>' . $e->getTraceAsString());
			}
			
			return $this->load(null, $pdo->lastInsertId());
		}
		
		
		/**
		 * @param string $pesquisa
		 * @param int $id
		 * @throws DaoException
		 * @return Pesquisa
		 */
		public function load($pesquisa = null, $id = null)
		{
			$pdo = ConexaoMysql::getInstance();
			if (is_null($id)) {
				$stm = $pdo->prepare('SELECT * FROM pesquisa WHERE pesquisa = ?');
				$stm->execute(array($pesquisa));
			} else if (is_null($pesquisa)){
				$stm = $pdo->prepare('SELECT * FROM pesquisa WHERE id = ?');
				$stm->execute(array($id));
			} else {
				$stm = $pdo->prepare('SELECT * FROM pesquisa WHERE id = ? AND pesquisa = ?');
				$stm->execute(array($id, $pesquisa));
			}
			if ($obj = $stm->fetchObject()) {
				return $this->setPesquisa($obj);
			} else {
				throw new DaoException('Não foi encontrado Sistema com a pesquisa = ' . $pesquisa);
			}
		}
		
		private function setPesquisa($obj)
		{
//			var_dump($obj);
//			die();
			$sistemaDao = new SistemaDao();
			$pesquisa = new Pesquisa();
			$pesquisa->setId($obj->id);
			$pesquisa->setSistema($sistemaDao->getById($obj->sistema_id));
			$pesquisa->setNumeroDeRequisicao($obj->numero_requisicoes);
			$pesquisa->setPesquisa($obj->pesquisa);
			$pesquisa->setResposta($obj->resposta);
			$pesquisa->setUltimaRequisicao(new \DateTime($obj->ultima_requisicao));
			return $pesquisa;
		}
	}
}