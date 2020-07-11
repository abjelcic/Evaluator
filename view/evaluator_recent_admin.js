    //ukljuci samo ako je admin, inace nista od koda ispit
    //razni handleri koji sluze za dodavanje novog zadataka

    $("button.archive").on("click",function(){
        $(this).parent().css("display","none");
        let problem_no = $(this).parent().find("div.data").html();

        ArchiveProblem( problem_no );
    });

    $("#start_new_problem").on("click",function(){
        $("#problem_title").css("display","");
        $(this).prop("disabled",true);
    });

    $("#submit_title").on( "click" , function(){
        ProblemTitle = $("#input_title").val();
        if( ProblemTitle !== "" )
        {
            $("div.input_wrap").css("display","");
        }
    });

    $("#submit_text").on("click",function(){
        ProblemText = $("div.input_wrap textarea").val();
        if( ProblemText !== "" )
        {
            $("#upload_files").css("display","");
            if( TestCaseNo === 0 )
            {
                TestCaseNo++;
                $("#upload_files").prepend( TestCaseFactory(TestCaseNo) );

                $("#new_problem_submit").css("display","");
            }
        }
    });





    function TestCaseFactory( test_case_no )
    {
        let p1 = $( "<p>Choose input file " + test_case_no + ": </p>");
        let input = $("<input form=\"forma\" type=\"file\" name=\"input_" + test_case_no + "\" id=\"input_" + test_case_no + "\">");
        input.on("change",function(){
            $(this).parent().parent().data("in",1);
            if( $(this).parent().parent().data("in") && $(this).parent().parent().data("out") )
            {
                $("#test_case_"+test_case_no).prop("disabled",false);
                $("#new_problem_submit").prop("disabled",false);
            }
        });
        p1.append( input );

        let p2 = $( "<p>Choose output file " + test_case_no + ": </p>");
        let output = $( "<input form=\"forma\" type=\"file\" name=\"output_" + test_case_no + "\" id=\"output_" + test_case_no + "\">" );
        output.on("change",function(){
            $(this).parent().parent().data("out",1);

            if( $(this).parent().parent().data("in") && $(this).parent().parent().data("out") )
            {
                $("#test_case_"+test_case_no).prop("disabled",false);
                $("#new_problem_submit").prop("disabled",false);
            }
        });
        p2.append( output );
        
        let p3 = $("<p>");
        let NextTestButton = $("<button>").html("Next test case").prop("disabled",true).attr("id","test_case_"+test_case_no).attr("type","button");
        NextTestButton.on("click",function(){
            TestCaseNo++;
            $("#upload_files").append( TestCaseFactory(TestCaseNo) );
            $(this).css("display","none");
            $("#new_problem_submit").prop("disabled",true);
        });
        p3.append( NextTestButton );

        return $("<section>").append("<hr>").append(p1).append(p2).append(p3).data("in",0).data("out",0);
    }

    function ArchiveProblem( problem_no )
    {
        $.ajax({
            
            url:       "https://rp2.studenti.math.hr/~abjelcic/projektni/model/archiveproblem.php",
            cache:     false,
            timeout:   10000,
            type:      "GET",
            dataType:  "json",
            data: {
                problem_no: problem_no
            },

            success: function( json )
            {
                if( "error" in json )
                {
                    console.log( json.error );
                }
                else
                {
                    if( json.NumberOfRecent == 0 )
                        $("#title").html("There are no recent problems!");
                }
            },
            
            error: function()
            {
                console.log("Ajax problem");
            }

        });
    }

