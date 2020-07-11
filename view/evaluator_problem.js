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
