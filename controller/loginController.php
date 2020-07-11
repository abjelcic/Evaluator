<?php 

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/../model/loginservice.class.php';


class LoginController
{
	public static function index() 
	{
		
		if( !LoginController::AlreadyLoggedIn() )
		{
			$FailMessage = '';
			
			//Trying to log in
			if( isset( $_POST['button'] ) && $_POST['button'] === 'login' )
			{
				$FailMessage = LoginService::LoginHandler( $_POST['username'] , $_POST['password'] );
				if( $FailMessage === '' )
				{
					//Login successful, rerun the webpage
					$UserHomePage = 'index.php?rt=evaluator/index';
					header( 'Location: ' . $UserHomePage );
					exit(0);
				}
			}

			//Trying to register
			else if( isset( $_POST['button'] ) && $_POST['button'] === 'register' )
			{
				$FailMessage = LoginService::RegisterHandler( $_POST['username'] , $_POST['password'] , $_POST['email']);
				if( $FailMessage === '' )
				{
					$title = 'Evaluator';
					$message = 'Check your e-mail to finish the registration';
					require_once __DIR__ . '/../view/login_index.php';
					exit(0);
				}
			}

			//Tryin to finish the registration
			else if( isset( $_GET['rt'] ) && $_GET['rt'] === 'register' )
			{
				$regseq = isset( $_GET['regseq'] ) ? $_GET['regseq'] : '';
				$FailMessage = LoginService::FinishRegistration( $regseq );
		
				$title = 'Registration';
				$message = $FailMessage === '' ? 'Registration completed successfully!' : $FailMessage;
				require_once __DIR__ . '/../view/login_register.php';
				exit(0);
			}
			
			//Remain on login page
	     	$title = 'Evaluator';
			$message = $FailMessage === '' ? 'Please login or register' : $FailMessage;
			require_once __DIR__ . '/../view/login_index.php';
			
			exit(0);
		}

	}

	private static function AlreadyLoggedIn()
	{
		if( isset( $_SESSION['ServerCode'] ) && isset( $_COOKIE['ClientCode'] ) )
		{
			$ServerCode = $_SESSION['ServerCode'];
			$ClientCode = $_COOKIE['ClientCode'];
			  
			if( $ServerCode === $ClientCode )
				return True;
	  	}
  
		return False;
	}

}; 

?>
