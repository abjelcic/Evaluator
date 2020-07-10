<?php

require_once __DIR__ . '/../model/evaluatorservice.class.php';

function sendJSONandExit( $message )
{
    header( 'Content-type:application/json;charset=utf-8' );
    echo json_encode( $message );
    flush();
    exit( 0 );
}


$message = [];
if( isset( $_GET["problem_no"] ) )
{
    $problem_no = $_GET["problem_no"];
    if( $problem_no<=0 || $problem_no>EvaluatorService::TotalNumberofProblems() )
    {
        $message["error"] = "Problem_no out of bounds! " . EvaluatorService::TotalNumberofProblems();
    }
    else
    {
        EvaluatorService::ArchiveValidProblem( $problem_no );
        $message[ "NumberOfRecent" ] = EvaluatorService::TotalNumberofRecentProblems();
    }
}
else
{
    $message['error'] = "proble_no not in GET";
}

sendJSONandExit( $message );






?>
