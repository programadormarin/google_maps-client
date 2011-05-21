<?php
set_include_path(
	get_include_path()
	. PATH_SEPARATOR . __DIR__ . '/../../'
	. PATH_SEPARATOR . __DIR__ . '/../../../utils/'
	
);

require_once 'application/br/com/lcobucci/utils/autoloader/NamespaceAutoloader.php';
br\com\lcobucci\utils\autoloader\NamespaceAutoloader::register();