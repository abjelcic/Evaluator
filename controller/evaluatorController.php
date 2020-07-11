<?php 

require_once __DIR__ . '/../model/evaluatorservice.class.php';

class EvaluatorController
{
	public static function index() 
	{
        //Default for chat/index
        EvaluatorController::About();
    }
    
    public static function About()
    {
        $selected     = "about";
        $username     = $_SESSION['username'];      
        $title        = "Welcome:";
        
        require_once __DIR__ . '/../view/evaluator_about.php';  
    }

    public static function Archives()
    {
        $selected       = "archives";
        $username       = $_SESSION['username'];      
        $title          = "Select a problem:";
        $ProblemsList   = EvaluatorService::GetArchievedProblems();
        
        $ProblemsSolved = [];
        foreach( $ProblemsList as $problem )
            $ProblemsSolved[] = EvaluatorService::HasAlreadySolved( $username , $problem->no );
        
        require_once __DIR__ . '/../view/evaluator_archives.php';
    }

    public static function Problem()
    {
        if( isset( $_GET['id_problem'] ) && EvaluatorService::IsValidProblem( $_GET['id_problem'] ) )
        {   
            $Problem = EvaluatorService::GetProblemByNo( $_GET['id_problem'] );

            $username     = $_SESSION['username']; 
            $title        = "Problem " . $Problem->no . ".)";
            
            $Solution = isset( $_FILES["Solution"] );

            $IsAlreadySolved = EvaluatorService::HasAlreadySolved( $username , $_GET['id_problem'] );

            require_once __DIR__ . '/../view/evaluator_problem.php';
        }
        else
        {
            header('Location: index.php?rt=_404');
        }
    }

    public static function NewProblemSubmit()
    {
        if( isset($_POST["problem_title"]) && isset($_POST["problem_text"]) && count($_FILES)>0 && count($_FILES)%2===0 )
        {
            $problem_title = $_POST["problem_title"];
            $problem_text = $_POST["problem_text"];
            
            EvaluatorService::AddNewProblem( $problem_title , $problem_text );
            
            $InputFileContents  = [];
            $OutputFileContents = [];
            $problem_no         = EvaluatorService::TotalNumberofProblems();

            for( $i = 1 ; $i <= count($_FILES)/2 ; $i++ )
            {
                if( !isset( $_FILES[ "input_" . $i ] ) || !isset( $_FILES[ "output_" . $i ] ) )
                {
                    $username     = $_SESSION['username']; 
                    $title = "Something went wrong with upload...try again!";
                    require_once __DIR__ . '/../view/404_index.php';
                    exit(0);
                }

                $InputFileContents[]  = trim( file_get_contents( $_FILES[ "input_" . $i ]['tmp_name'] ) );
                $OutputFileContents[] = trim( file_get_contents( $_FILES[ "output_" . $i ]['tmp_name'] ) );
            }
            
            //echo "<pre>";
            //var_dump( $problem_title );
            //var_dump( $problem_text );
            //echo "</pre>";

            if( !EvaluatorService::AddTestCases( $problem_no , $InputFileContents , $OutputFileContents ) )
            {
                $username     = $_SESSION['username']; 
                $title = "Something went wrong with creating test cases...try again!";
                require_once __DIR__ . '/../view/404_index.php';
                exit(0);
            }
            else
            {
                $username     = $_SESSION['username'];      
                $title        = "New problem is available! Check recent problems!";
                
                require_once __DIR__ . '/../view/evaluator_newproblemsubmited.php';  
            }


        }
        else
        {
            $username     = $_SESSION['username']; 
            $title = "Something went wrong with upload...try again!";
            require_once __DIR__ . '/../view/404_index.php';
            exit(0);
        }
    }

    public static function Solved()
    {
        if( isset( $_POST["solved_password"] ) )
        {
            $ans = EvaluatorService::UpdateProblemSolved( $_SESSION['username'] , $_POST["solved_password"] );
            

            $username    = $_SESSION['username'];
            $title       = $ans ? "Great work!" : "Something went wrong...!";
            $oder_solved = EvaluatorService::GetNumberOfTimesSolved( EvaluatorService::GetProblemByPassword($_POST["solved_password"]) );


            require_once __DIR__ . '/../view/evaluator_solved.php';  
        }
        else
        {
            header('Location: index.php?rt=_404');
        }
    }

    public static function Progress()
    {
        $selected     = "progress";
        $username     = $_SESSION['username'];      
     
        $solvedProblems = EvaluatorService::ProblemsSolvedByUser( $username );
        $totalProblems  = EvaluatorService::TotalNumberofProblems();

        $title        = "You solved " . $solvedProblems . " out of " . $totalProblems . " problems!";
        $ProblemsList = EvaluatorService::GetAllProblems();


        $ProblemsSolved = [];
        foreach( $ProblemsList as $problem )
            $ProblemsSolved[] = EvaluatorService::HasAlreadySolved( $username , $problem->no );
        
        $ProblemsSolvedTime = [];
        foreach( $ProblemsList as $problem )
            $ProblemsSolvedTime[] = EvaluatorService::TimeSolved( $username , $problem->no );
        
        //echo "<pre>";
        //echo var_dump($ProblemsSolved);
        //echo "</pre>";


        require_once __DIR__ . '/../view/evaluator_progress.php';
    }

    public static function News()
    {
        $selected     = "news";
        $username     = $_SESSION['username'];      
        $title        = "News:";
        
        require_once __DIR__ . '/../view/evaluator_news.php';  
    }

    public static function Recent()
    {
        $selected       = "recent";
        $username       = $_SESSION['username'];      
        $title          = "Select a problem:";
        $ProblemsList   = EvaluatorService::GetRecentProblems();
        
        $ProblemsSolved = [];
        foreach( $ProblemsList as $problem )
            $ProblemsSolved[] = EvaluatorService::HasAlreadySolved( $username , $problem->no );
        
        $IsAdmin        = EvaluatorService::IsAdmin( $username );

        require_once __DIR__ . '/../view/evaluator_recent.php';
    }

    public static function Logout()
    {
        session_unset();
        session_destroy();
        if( isset( $_COOKIE['ClientCode'] ) )
        {
            unset( $_COOKIE['ClientCode'] );
            setcookie( 'ClientCode' , null , -1 , '/' ); 
        }
        header('Location: index.php');
    }

}; 

?>