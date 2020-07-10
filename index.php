<?php 
	session_start();


	//If any related web-page is opened, first check login status
	require_once __DIR__ . '/controller/loginController.php';
	$con = new LoginController();
	$con->index();

	
	//Only if the user is logged  in, allow him acces to other pages
	//index.php?rt=controller/action


	if( isset( $_GET['rt'] ) )
		$route = $_GET['rt'];
	else
		$route = 'chat';

	$parts = explode( '/', $route );

	$controllerName = $parts[0] . 'Controller';
	if( isset( $parts[1] ) )
		$action = $parts[1];
	else
		$action = 'index';

	$controllerFileName = 'controller/' . $controllerName . '.php';

	if( !file_exists( $controllerFileName ) )
	{
		$controllerName = '_404Controller';
		$controllerFileName = 'controller/' . $controllerName . '.php';
	}

	require_once $controllerFileName;

	$con = new $controllerName; 

	if( !method_exists( $con, $action ) )
		$action = 'index';

	$con->$action();

?>
