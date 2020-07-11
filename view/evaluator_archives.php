<?php require_once __DIR__ . '/evaluator_header.php'; ?>

    
    <h2><?php echo $title;?></h2>
    
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
                        
            echo "</div>\n";
            echo "<br>\n\n";
            
            $i++;
        }
    ?>




    <script>
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
                    let link = "index.php?rt=evaluator/problem&id_problem=" + $(this).data("no");
                    window.location.href = link;
                });
                Problem.append( SolveBtn );
            }
        });
    </script>
    

    
<?php require_once __DIR__ . '/evaluator_footer.php'; ?>