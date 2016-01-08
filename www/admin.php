<?php
if(!session_start()){session_start();};
define('BASE_DIR', realpath(dirname(dirname(__file__))));

require BASE_DIR . '/src/requet.php';
require BASE_DIR . '/src/function.php';
require BASE_DIR . '/src/validation.php';

if($connection === 'ok')
{
	require BASE_DIR . '/src/controller_admin.php';
	require BASE_DIR . '/src/core_admin.php';
}
else
{
	header('Location:connection_admin.php');
}