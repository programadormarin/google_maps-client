<?php
namespace dao
{
	use dao\ConexaoMysql;
	use entities\Sistema;
	use dao\Dao;
	
	class SistemaDao extends Dao
	{
		
		/**
		 * @param Sistema $sistema
		 * @throws Exception
		 * @return Sistema
		 */
		public function save(Sistema $sistema)
		{
			$hash = $this->criaHash($sistema->getKey());
			$pdo = ConexaoMysql::getInstance();
			$stm = $pdo->prepare('INSERT INTO sistema (nome, chave) VALUES (?, ?);');
			$stm->bindValue(1, $sistema->getNome());
			$stm->bindValue(2, $this->criaHash($sistema->getKey(), 'hermenegildo'));
			try {
				$stm->execute();
			} catch (Exception $e){
				throw new DaoException('Não foi possível inserir novo sistema no banco de dados.');
			}
			return $this->getById($pdo->lastInsertId());
		}
		
		/**
		 * @param string $nome
		 * @param string $chave
		 * @throws Exception
		 * @return Sistema
		 */
		public function load($nome, $chave)
		{
			$pdo = ConexaoMysql::getInstance();
			$stm = $pdo->prepare('SELECT * FROM sistema WHERE nome = ? AND chave = ?');
			$stm->bindValue(1, $nome);
			$stm->bindValue(2, $this->criaHash($chave, 'hermenegildo'));
			$stm->execute();
			if ($obj = $stm->fetchObject()) {
				$sistema = new Sistema();
				$sistema->setId($obj->id);
				$sistema->setNome($obj->nome);
				$sistema->setKey($obj->chave);
				return $sistema;
			} else {
				throw new DaoException('Não foi encontrado Sistema com nome ou chave passado.');
			}
		}
		
		/**
		 * @param int $id
		 * @throws Exception
		 * @return Sistema
		 */
		public function getById($id = null)
		{
			if (is_numeric($id)) {
				$pdo = ConexaoMysql::getInstance();
				$stm = $pdo->prepare('SELECT * FROM sistema WHERE id = ?;');
				$stm->execute(array($id));
				if ($obj = $stm->fetchObject()) {
					$sistema = new Sistema();
					$sistema->setId($obj->id);
					$sistema->setNome($obj->nome);
					$sistema->setKey($obj->chave);
					return $sistema;
				} else {
					throw new DaoException('Não foi encontrado Sistema com nome ou chave passado.');
				}
			} else {
				$pdo = ConexaoMysql::getInstance();
				$stm = $pdo->prepare('SELECT * FROM sistema;');
				$stm->execute();
				if ($obj = $stm->fetchObject()) {
					$sistema = new Sistema();
					$sistema->setId($obj->id);
					$sistema->setNome($obj->nome);
					$sistema->setKey($obj->chave);
					return $sistema;
				} else {
					throw new DaoException('Não foi encontrado Sistema com nome ou chave passado.');
				}
			}
		}
		
		/**
		 * @param string $string
		 * @param string $salt
		 * @return string
		 */
		protected function criaHash($string, $salt = null)
		{
			if (is_null($salt)) $salt = uniqid();
			
			$hash = $salt;
			
			for ($i = 0; $i < 1000; ++$i) $hash = md5($hash . $string);
			
			return $salt . $hash;
		}
	}
}