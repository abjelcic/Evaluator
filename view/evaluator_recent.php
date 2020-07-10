<?php require_once __DIR__ . '/chat_header.php'; ?>

    
    <h2 id="title"><?php echo (count($ProblemsList) !== 0 ) ? $title : "There are no recent problems!";?></h2>
    
    <?php
        $i = 0;
        foreach( $ProblemsList as $problem )
        {
            echo "<div class=\"channel\">\n";
            
            echo "<h3>" . $problem->no . ".) ". $problem->title . "</h3> ";
            echo "<p>" . (($ProblemsSolved[$i])?"(Already solved)":"")  . "</p>";

            echo "<div class=\"data\" style=\"display:none;\">" . $problem->no . "</div>";
            echo "<div class=\"noselect problemtext\" style=\"display: none;\">" . $problem->text;
            echo "</div>";

            //$link = "index.php?rt=channel/show&id_channel=" . $problem->no;
            //echo "<a href=\"" . $link  . "\">Enter channel: <b>\"" . $problem->title . "\"</b></a>\n";      
            
            if( $IsAdmin )
                echo "<button class=\"archive\">Archive problem!</button>";

            echo "</div>\n";
            echo "<br>\n\n";
            
            $i++;
        }

        echo "<hr>";


        if( $IsAdmin )
        {
            ?>
                
                <div>
                    <h3>Add new problem <button id="start_new_problem">Start!</button> </h3>
                </div>

                <div id="problem_title" style="display: none;">
                    <p>  
                        Insert the title of a new problem:
                        <input id="input_title" type="text" name="problem_title" id="" form="forma">
                        <button id="submit_title">Save problem title</button>
                    </p>
                </div>

                <div class="input_wrap" style="display: none;">
                    <p>Type in problem text:</p>
                    <textarea name="problem_text" form="forma"></textarea>   
                    <p><button id="submit_text">Save problem text</button></p>
                <div class="input_wrap">




                <form id="forma" action="index.php?rt=chat/newproblemsubmit" method="post" enctype="multipart/form-data">
                <div id="upload_files" style="display: none;">
                </div>
                <hr>
                <button type="submit" id="new_problem_submit" style="display: none;" disabled>Submit new problem!</button>
                </form>


            <?php
        }
    ?>






    <script>

        var ProblemText  = "";
        var ProblemTitle = "";
        var TestCaseNo   = 0;

        $(document).ready(function(){

            let Problems = $("div.channel");
            for(let i=0; i<Problems.length; ++i)
            {

                let Problem = Problems.eq(i);
                
                var problem_no = Problem.find("div.data").html();

                Problem.hover( function(){ $(this).css( 'filter' , 'brightness(110%)');  } ,
                               function(){ $(this).css( 'filter' , 'brightness(100%)');  }     );

                Problem.on( "click" , function(){

                    let text = $(this).find("div.problemtext.noselect");
                    let visibility = ( text.css("display") === "none" ) ? "" : "none";

                    text.css("display", visibility );
                });
                
                let SolveBtn = $("<button>");
                SolveBtn.html( "Open problem" )
                        .css("float","right")
                        .data("no",problem_no);


                SolveBtn.on("click",function()
                {
                    let link = "index.php?rt=chat/problem&id_problem=" + $(this).data("no");
                    window.location.href = link;
                });
                Problem.append( SolveBtn );
                


            
            }

            <?php if( $IsAdmin ) echo "$.getScript('./view/evaluator_recent_admin.js')";?>

        });







    </script>
    
    
<?php require_once __DIR__ . '/chat_footer.php'; ?>