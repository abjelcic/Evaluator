<?php

function sendJSONandExit( $message )
{
    header( 'Content-type:application/json;charset=utf-8' );
    echo json_encode( $message );
    flush();
    exit( 0 );
}


if( FALSE )
{
    $_POST["solutioncode"] =
    '
    #include <stdio.h>
    int main(void)
    {
        int n;
        scanf("%d", &n);
        printf("%d",(n*(n+1))/2);
        return 0;
    }
    ';

    $_POST["problem_no"] = 1;
}




$message = [];

if( isset( $_POST["problem_no"] ) && isset( $_POST["solutioncode"] ) )
{
    $problem_no   = $_POST["problem_no"];
    $solutioncode = $_POST["solutioncode"];


    
    file_put_contents( "../../../../tmp/tmp.c" , html_entity_decode( $solutioncode ) );     
    
 
    exec( "cd ../../../../tmp/ && gcc tmp.c -o run" , $output, $return_value );

    //echo ($return_value == 0 ? 'OK' : 'Error: status code ' . $return_value) . PHP_EOL;
    //echo 'Output: ' . PHP_EOL . implode(PHP_EOL, $output);


    if( $return_value !== 0 )
    {
        $message['error'] = "compilation error";
    }
    else
    {//sada je kod kompiliran, vrtim po test cases

        
        $fi = new FilesystemIterator( "../../../../data/problems/problem_" . $problem_no , FilesystemIterator::SKIP_DOTS );
        $no_test_cases = iterator_count( $fi )/2;
        $no_test_cases_success = 0;
        
        for( $test_case = 1 ; $test_case <= $no_test_cases ; $test_case++ )
        {

            //$filename_out = "../../../../data/problems/problem_" . $problem_no . "/output_" . $test_case;
            //$output_correct = fopen( $filename_out , "r" );
            

            //pokrecem kod
            $filename_in = "../data/problems/problem_" . $problem_no . "/input_" . $test_case;
            $timeout = exec( "cd ../../../../tmp; timeout 5s ./run < " . $filename_in . " > out.txt; echo $?" , $output , $return_value );
             
            //print_r($return_value);

            if( $timeout != 0 )
            {
                $message['error'] = "timeout error";// . $timeout;
                break;
            }
            else
            {
                $f1 = "../../../../tmp/out.txt";
                $f2 = "../../../../data/problems/problem_" . $problem_no . "/output_" . $test_case;
                $fout         = fopen( $f1 , "r" );
                $fout_correct = fopen( $f2 , "r" );
                
                if( $fout && $fout_correct )
                {
                    $s1 = fread($fout,filesize($f1));
                    $s2 = fread($fout_correct,filesize($f2));

                    if( $s1!== FALSE && $s2!==FALSE )
                    {
                        if( trim($s1) === trim($s2) )
                            $no_test_cases_success++;
                    }

                    fclose( $fout );
                    fclose( $fout_correct );
                }
                


            }
        
        }

        $message['success'] = $no_test_cases_success . "/" . $no_test_cases;
        if( $no_test_cases_success === $no_test_cases )
        {
            $message['code'] = password_hash( "sol" . "problem_" . $problem_no , PASSWORD_DEFAULT );
        }


    }




}
else
{
    $message['error'] = "Problem id invalid";
}




sendJSONandExit( $message );






?>
