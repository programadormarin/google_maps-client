<?php
namespace config
{
	class HttpRequest {
	
		private $url;
		private $method;
		private $params;
	
		public function __construct($url, $method, array $params = array()) {
			$this->url = $url;
			$this->method = $method;
			$this->params = $params;
		}
	
		public function load() {
			$url = $this->url;
	
			$handler = curl_init();
	
			if ($method = $this->verifyMethod()){
				if ($method == 'GET') {
					$url = $this->url . '?' . http_build_query($this->params);
				} else {
					if ($method == 'POST') {
						curl_setopt($handler, CURLOPT_POST, $this->verifyMethod());
					} else {
						curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $method);
					}
	
					curl_setopt($handler, CURLOPT_POSTFIELDS, $this->params);
				}
			} else {
				throw new Exception(
					'This method is not a valid method.' . "\n" . ' 
					Valid methods: POST, GET, PUT or DELETE.'
					);
			}
	
			curl_setopt($handler, CURLOPT_URL, $url);
			curl_setopt($handler, CURLOPT_HEADER, false);
			curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($handler, CURLOPT_TIMEOUT, 10);
			$httpStatus = curl_getinfo($handler, CURLINFO_HTTP_CODE);
			$output = curl_exec($handler);
			$this->verifyHttpResponse($httpStatus);
			curl_close($handler);
			
			return $output;
		}
	
		private function verifyMethod() {
			$method = strtoupper($this->method);
			switch ($method){
				case 'POST': return $method;
				case 'GET': return $method;
				case 'PUT': return $method;
				case 'DELETE': return $method;
				default: return false;
			}
		}
	
		private function verifyHttpResponse($httpStatus){
			switch ($httpStatus){
				case'400';
				throw new ErrorException("BAD REQUEST",400);
				case'401';
				throw new ErrorException("NÃO AUTORIZADO",401);
				case'403';
				throw new ErrorException("PROIBIDO",403);
				case'404';
				throw new ErrorException("NÃO ENCONTRADO",404);
				case'500';
				throw new ErrorException("ERRO INTERNO DO SERVIDOR",500);
				case'501';
				throw new ErrorException("NÃO IMPLEMENTADO",501);
			}
		}
	}
}