<?php
//$data = new DateTime();
//var_dump($data->format('Y-m-d H:i:s'));die;

require_once 'application/config/autoload.php';

use control\GoogleClient;

try {
	$a = new GoogleClient();
} catch (Exception $e) {
	echo '<p>N�o foi poss�vel fazer sua pesquisa pelo seguinte motivo: </p>';
	echo $e->getMessage();
}