<?php
namespace dao {
	use PDO;
	/**
	 * Arquivo para exemplificar o uso do padrão singleton
	 */
	
	/**
	 * Exemplo de classe singleton (conexão MySQL via PDO)
	 *
	 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
	 * @method PDOStatement prepare
	 * @method array query
	 * @method int lastInsertId
	 */
	class ConexaoMysql {
		/**
		 * Host da conex�o
		 *
		 * @var string
		 */
		const HOST = 'localhost';
	
		/**
		 * Banco de dados que ser� conectado
		 *
		 * @var string
		 */
		const DB = 'googleclient';
	
		/**
		 * Usuário a ser utilizado durante a conex�o
		 *
		 * @var string
		 */
		const USER = 'root';
	
		/**
		 * Senha a ser utilizada durante a conex�o
		 *
		 * @var string
		 */
		const PASS = 'admin';
	
		/**
		 * Instância única da classe
		 *
		 * @var ConexaoMysql
		 */
		private static $instance;
	
		/**
		 * Conexão com o banco de dados
		 *
		 * @var PDO
		 */
		private $connection;
	
		/**
		 * Verifica se a instância já foi criada, cria
		 * caso ela não tenha sido e retorna ela
		 *
		 * @return ConexaoMysql
		 */
		public static function getInstance()
		{
			if (is_null(self::$instance)) {
				self::$instance = new ConexaoMysql();
			}
	
			return self::$instance;
		}
	
		/**
		 * Construtor da classe (privado para que a classe apenas possa
		 * ser instanciada de dentro dela)
		 *
		 * Inicia a conexão e define que os erros de query/conexão serão tratados com exceptions
		 */
		private function __construct()
		{
			$this->connection = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DB, self::USER, self::PASS);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	
		/**
		 * Método mágico para redirecionar todas as chamadas para
		 * a instância da classe PDO
		 *
		 * @param string $metodo Nome do método
		 * @param array $parametros Parâmetros utilizados
		 * @return mixed Retorno do método chamado
		 * @throws BadMethodCallException Erro quando o método chamado não existe
		 */
		public function __call($metodo, array $parametros)
		{
			if (!method_exists($this->connection, $metodo)) {
				throw new BadMethodCallException('Não existe nenhum método com o nome ' . $metodo);
			}
	
			return call_user_func_array(array($this->connection, $metodo), $parametros);
		}
	}
}