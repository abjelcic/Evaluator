<?php

require_once __DIR__ . '/../app/database/db.class.php';

require_once __DIR__ . '/../model/problem.class.php';


class EvaluatorService
{
    public static function GetArchievedProblems()
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM Archives WHERE recent!=1 ORDER BY no' );
          $st->execute();
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        $ProblemsList = [];
        while( $row = $st->fetch() )
        {
            $ProblemsList[] = new Problem( $row['no'] , $row['title'] , $row['text'] , $row['solved'] );
        }

        return $ProblemsList;
    }

    public static function GetRecentProblems()
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT * FROM Archives WHERE recent=1 ORDER BY no' );
        $st->execute();
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

      $ProblemsList = [];
      while( $row = $st->fetch() )
      {
          $ProblemsList[] = new Problem( $row['no'] , $row['title'] , $row['text'] , $row['solved'] );
      }

      return $ProblemsList;
    }

    public static function GetAllProblems()
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT * FROM Archives ORDER BY no' );
        $st->execute();
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

      $ProblemsList = [];
      while( $row = $st->fetch() )
      {
          $ProblemsList[] = new Problem( $row['no'] , $row['title'] , $row['text'] , $row['solved'] );
      }

      return $ProblemsList;
    }

    public static function IsValidProblem( $no_problem )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM Archives WHERE no=:no_problem' );
          $st->execute( [ 'no_problem' => $no_problem ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
    
        return (bool)( $st->fetch() ); 
    }

    public static function GetProblemByNo( $no_problem )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM Archives WHERE no=:no_problem' );
          $st->execute( [ 'no_problem' => $no_problem ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
        
        $row = $st->fetch();
        return new Problem( $row['no'] , $row['title'] , $row['text'] , $row['solved'] );
        
    }

    public static function UpdateProblemSolved( $username , $solved_password )
    {

        $problem_no = EvaluatorService::GetProblemByPassword( $solved_password );
        if( $problem_no == "false" )
            return FALSE;

        if( EvaluatorService::HasAlreadySolved( $username , $problem_no ) )
          return FALSE;
        

        EvaluatorService::IncrementNumberofSolvedTimes( $problem_no );

        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'INSERT INTO Solved (username,problem_no) VALUES (:username,:problem_no)' );
          $st->execute( [ 'username' => $username , 'problem_no' => $problem_no ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }


        return TRUE;
    }

    public static function GetProblemByPassword( $problem_password )
    {
      $MaxNProblems = 500;
      for( $i = 1 ; $i <= $MaxNProblems ; $i++ )
      {
        if( password_verify( "sol" . "problem_" . $i , $problem_password ) )
          return $i;
      }

      return "false";
    }

    public static function HasAlreadySolved( $username , $problem_no )
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT * FROM Solved WHERE username=:username AND problem_no=:problem_no' );
        $st->execute( [ 'username' => $username , 'problem_no' => $problem_no ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

      $ans = $st->fetch();
      
      //echo "<pre>";
      //echo $username . $problem_no;
      //var_dump( (bool)$ans );
      //echo "</pre>";

      return (bool)( $ans ); 
    }

    public static function IncrementNumberofSolvedTimes( $problem_no )
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'UPDATE Archives SET solved=solved+1 WHERE no=:problem_no' );
        $st->execute( [ 'problem_no' => $problem_no ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
    }

    public static function GetNumberOfTimesSolved( $problem_no )
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT solved FROM Archives WHERE no=:problem_no' );
        $st->execute( [ 'problem_no' => $problem_no ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

      $row = $st->fetch();
      return $row["solved"];

    }

    public static function TimeSolved( $username , $problem_no )
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT * FROM Solved WHERE username=:username AND problem_no=:problem_no' );
        $st->execute( [ 'username' => $username , 'problem_no' => $problem_no ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

      if( !( $row = $st->fetch() ) )
        return FALSE;

      return $row['time'];
    }

    public static function ProblemsSolvedByUser( $username )
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT COUNT(*) FROM Solved WHERE username=:username GROUP BY username' );
        $st->execute( [ 'username' => $username  ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

      $ans = $st->fetch();

      if( !$ans )
        return 0;

      return $ans[0];
    }


    public static function TotalNumberofProblems()
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT * FROM Archives' );
        $st->execute();
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
      
      $ans = $st->fetchAll();

      return count( $ans );
    }

    public static function IsAdmin( $username )
    {
      
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT admin FROM dz2_users WHERE username=:username' );
        $st->execute( [ 'username' => $username ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

      $ans = $st->fetch();

      if( $ans['admin'] == 1 )
        return TRUE;

      return FALSE;

    }

    public static function ArchiveValidProblem( $problem_no )
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'UPDATE Archives SET recent=0 WHERE no=:problem_no' );
        $st->execute( [ 'problem_no' => $problem_no ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }


    }

    public static function TotalNumberofRecentProblems()
    {
      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'SELECT COUNT(*) FROM Archives WHERE recent=1 GROUP BY recent' );
        $st->execute();
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
      
      $ans = $st->fetch();

      if( !$ans )
        return 0;

      return $ans[0];
    }

    public static function AddNewProblem( $problem_title , $problem_text )
    {

      $no = EvaluatorService::TotalNumberofProblems();
      $no++;

      $db = DB::getConnection();
      try
      {
        $st = $db->prepare( 'INSERT INTO Archives (no,text,title,solved,recent) VALUES (:no,:text,:title,:solved,:recent)' );
        $st->execute( [ "no" => $no , "text" => $problem_text , "title" => $problem_title , "solved" => 0 , "recent" => 1 ] );
      }
      catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
    }

    public static function AddTestCases( $problem_no , $InputFileContents , $OutputFileContents )
    {

      //file_put_contents( "../../../../tmp/tmp.c" , html_entity_decode( $solutioncode ) );     
    
 
      //echo shell_exec( "mkdir ../../../../data/problems/toni"  );
      //echo mkdir( __DIR__ . "../../../../data/problems/toni" );
      
      exec( "rm -rf ../../../data/problems/problem_" .  $problem_no . ";" .
            "mkdir ../../../data/problems/problem_" .   $problem_no . ";" .
            "chmod a+w ../../../data/problems/problem_" .  $problem_no, $output, $return_value );
      if( $return_value !== 0 )
        return FALSE;
      
      if( count($InputFileContents) !== count($OutputFileContents) )
        return FALSE;

      for( $i = 0 ; $i < count($InputFileContents) ; $i++ )
      {
        $input_content  = $InputFileContents[$i];
        $output_content = $OutputFileContents[$i];

        exec( "touch ../../../data/problems/problem_" . $problem_no . "/input_" . ($i+1) , $output , $return_value );
        if( $return_value !== 0 )
          return FALSE;
        exec( "touch ../../../data/problems/problem_" . $problem_no . "/output_" . ($i+1) , $output , $return_value );
        if( $return_value !== 0 )
          return FALSE;

        if( file_put_contents( "../../../data/problems/problem_" . $problem_no . "/input_" . ($i+1) , html_entity_decode( $input_content ) ) === FALSE )     
          return FALSE;
        if( file_put_contents( "../../../data/problems/problem_" . $problem_no . "/output_" . ($i+1) , html_entity_decode( $output_content ) ) === FALSE )     
          return FALSE;
      }
      
      return TRUE;
    }



};

?>
