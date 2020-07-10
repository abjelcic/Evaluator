<?php 

class _404Controller
{
	public function index( $ErrorMessage = '' ) 
	{
		$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : '';
		$title = 'Page not found.';
        
		require_once __DIR__ . '/../view/404_index.php';
	}
}; 

?>
