<?php

require_once __DIR__ . '/../app/database/db.class.php';


class LoginService
{

  public static function LoginHandler( $username , $password )
  {
    if( !preg_match( '/[a-zA-Z]{1,20}/', $username ) )
      return 'Username not valid!';
    
    if( $password === '' )
      return 'Empty password!';
    

    $db = DB::getConnection();
    try
    {
      $st = $db->prepare( 'SELECT * FROM dz2_users WHERE username=:username' );
      $st->execute( [ 'username' => $username ] );
    }
    catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
    
    $row = $st->fetch();
    if( $row === false )
      return 'Wrong username or password.';

    if( !$row['has_registered'] )
      return 'Please confirm your registration!';


    $hash = $row[ 'password_hash' ];
    if( !password_verify( $password , $hash )  )
      return 'Wrong username or password.';




    //Successfull login
    $Code = LoginService::GenerateRandomString();
    $_SESSION['ServerCode'] = md5( $Code );
    setcookie( 'ClientCode' , md5( $Code ) , time()+60*60 , '/');

    $_SESSION['username'] = $username;
    $_SESSION['id']       = $row['id'];
    //FailMessage empty
    return '';
  }

  public static function RegisterHandler( $username , $password , $email )
  {
    if( !preg_match( '/^[a-zA-Z]{1,20}$/', $username ) )
      return 'Username not valid!'; 
    
    if( $password === '' )
      return 'Empty password!';
    
    if( !filter_var( $email , FILTER_VALIDATE_EMAIL ) )
      return 'E-mail not valid!';

    $db = DB::getConnection();
    try
    {
      $st = $db->prepare( 'SELECT * FROM dz2_users WHERE username=:username' );
      $st->execute( [ 'username' => $username ] );
    }
    catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

    if( $st->rowCount() > 0 )
      return 'Username already exists'; // 'Username not valid';


      try
      {
        $st = $db->prepare( 'INSERT INTO dz2_users (username, password_hash, email, registration_sequence, has_registered) VALUES (:username, :hash, :email, :regseq, :hasreg  )' );
        $hash = password_hash( $password , PASSWORD_DEFAULT );
        $regseq = LoginService::GenerateRandomString(20);
  
        $st->execute( [ 'username' => $username, 
                        'hash'     => $hash,
                        'email'    => $email,
                        'regseq'   => $regseq,
                        'hasreg'   => 0         
                      ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }


      if( !LoginService::SendRegistrationEmail( $email , $regseq ) )
        return 'Sending registration e-mail failed!';
      

      // Registration e-mail sent successfully
      return '';
  }

  public static function FinishRegistration( $regseq )
  {
      $db = DB::getConnection();

      try
      {
          $st = $db->prepare( 'SELECT * FROM dz2_users WHERE registration_sequence=:regseq' );
          $st->execute( [ 'regseq' => $regseq ] );
      }	
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
      
      
      if( $st->rowCount() === 0 )
      {
          return 'Registration sequence not valid!';
      }
      else
      {
          $row = $st->fetch();
          if( $row['has_registered'] )
              return 'User already registered!'; 
  
  
          try
          {
              $st = $db->prepare( 'UPDATE dz2_users SET has_registered=:hs WHERE id=:id' );
              $st->execute( [ 'hs' => 1 , 'id' => $row['id'] ] );
          }	
          catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
  
          
          //Registration finished successfully
          return '';
      }
  
  }









  private static function GenerateRandomString( $length = 10 )
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen( $characters );
    
    $randomString = '';
    for ($i = 0; $i < $length; $i++)
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    
    return $randomString;
  }

  private static function SendRegistrationEmail( $emailaddress , $code )
  {
    $link = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "?rt=register&regseq=" . $code;

    $to      = $emailaddress;
    $subject = 'Registration on Chat';
    $message = 'Click here: ' . $link .
               ' to finish your registration' . ".\n";

    return mail( $to , $subject , $message );
  }

};






?>
