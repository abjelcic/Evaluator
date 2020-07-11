<?php require_once __DIR__ . '/evaluator_header.php'; ?>

    
    <h2><?php echo $title;?></h2>
    <p><?php echo "Solved by " . $Problem->solved . " members"; ?></p>

    <div id="problem_no" style="display: none;"><?php echo $Problem->no; ?></div>

    <hr>

    <h1 style="text-align: center;" > <?php echo $Problem->title ?> </h1>    
    <section id="problemtext">
        <?php
            echo $Problem->text;
        ?>
    </section>
    

    <br>
    <hr>
    
    <section id="upload" style="display: <?php echo $IsAlreadySolved ? "none" : ""; ?>">
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
            <p>
            Select file to upload:
            </p>

            <p>
            <input type="file" name="Solution" id="Solution">
            </p>

            <p>
            <input type="submit" value="Upload solution!" name="submit">
            </p>
            
        </form>
    </section>

    <section style="display: <?php echo $IsAlreadySolved ? "" : "none"; ?>; font-weight: bold;">
        You already solved this problem!
    </section>

    <hr>



    <form action="index.php?rt=evaluator/solved" method="post" style="display: none;" id="solved_password">
        <input type="text" name="solved_password">
    </form>



    <?php
        if( $Solution )
        {
            $phpFileUploadErrors = array(
                "0" => 'The file uploaded with success',
                "1" => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                "2" => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                "3" => 'The uploaded file was only partially uploaded',
                "4" => 'No file was uploaded',
                "6" => 'Missing a temporary folder',
                "7" => 'Failed to write file to disk.',
                "8" => 'A PHP extension stopped the file upload.',
            );
            
            if( $_FILES["Solution"]["error"] !== 0 )
            {
                echo $phpFileUploadErrors[ $_FILES["Solution"]["error"] ] . "\n";
            }
            else
            {
                $uploadOk = 1;
                
                if( $_FILES["Solution"]["size"] > 100000 ) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                
                if( pathinfo( basename($_FILES["Solution"]["name"]) , PATHINFO_EXTENSION ) !== "c" )
                {
                    echo "Sorry, only .c extension allowed.";
                    $uploadOk = 0;
                }

                if( $uploadOk )
                {
                    echo "<div id=\"code\" style=\"display: none;\">";
                    echo "<pre>";
                    echo htmlspecialchars( file_get_contents( $_FILES['Solution']['tmp_name'] ) );
                    echo "</pre>";
                    echo "</div>";
                }
            }

        }
    ?>
    


    <script>
        <?php echo "$.getScript('./view/evaluator_problem.js')"; ?>
    </script>

<?php require_once __DIR__ . '/evaluator_footer.php'; ?>
