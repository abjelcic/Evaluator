<?php require_once __DIR__ . '/chat_header.php'; ?>

    
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



    <form action="index.php?rt=chat/solved" method="post" style="display: none;" id="solved_password">
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
        $(document).ready(function(){
            $("#code").prepend( $("<div id=\"message\" style=\"font-weight: bold;\"></div>") );
            $("#code").prepend( $("<h2>Your solution:</h2>") );
            $("#code").css( "display" , "block" );
            $("#code").append( $("<p id=\"check\"><button>Check solution!</button></p>") );

            $("#code button").on("click",function(){
                $(this).prop("disabled",true);

                $.ajax({
                    url: "https://rp2.studenti.math.hr/~abjelcic/projektni/model/compilenrun.php",
                    cache: false,
                    timeout: 10000,
                    type: "POST",
                    dataType: "json",
                    
                    data:
                    {
                        problem_no: $("#problem_no").html(),
                        solutioncode: $("#code pre").html()
                    },
                    
                    success: function( json )
                    {
                        if( 'error' in json )
                        {
                            let errmess = json.error;
                            if( errmess === "compilation error" )
                            {
                                $("#message").html( "Compilation error! Check your code and resend!" );
                            }
                            else if( errmess === "timeout error" )
                            {
                                $("#message").html( "Server timeout! Check your code and resend!" );
                            }
                            else
                            {
                                $("#message").html( errmess );
                            }    
                        }
                        else
                        {
                            let success = json.success;
                            let solved = success.split("/")[0];
                            let total  = success.split("/")[1]
                            
                            if( solved !== total )
                            {
                                $("#message").html( "Correctly solved " + solved + " out of " + total + " test cases! Check your code and resend!"  );
                            }
                            else
                            {
                                let solved_password = json.code;
                                //console.log( solved_password );
                                $("#solved_password input").val( solved_password );
                                $("#solved_password").submit();
                            }

                        }
                    },

                    error: function()
                    {
                        $("#message").html( "Error with server communication" );
                    }
                });

                $("#check").append( $("<span> Waiting for server response...</span>").addClass("wait") );
                
            });
        });


        $(document).ajaxStop(function(){
            $("#code").find("span.wait").remove();
            $("#code button").prop("disabled",false);
        });
    </script>

<?php require_once __DIR__ . '/chat_footer.php'; ?>