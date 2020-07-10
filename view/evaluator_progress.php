<?php require_once __DIR__ . '/chat_header.php'; ?>

    
    <h2><?php echo $title;?></h2>
    
    <?php
        $i = 0;
        foreach( $ProblemsList as $problem )
        {

            if( $ProblemsSolved[$i] === FALSE )
            {
                $i++;
                continue;
            }
            
            echo "<div class=\"channel\">\n";
            
            echo "<h3>" . $problem->no . ".) ". $problem->title . "</h3> ";

            echo "<div class=\"data\" style=\"display:none;\">" . $problem->no . "</div>";
            echo "<div class=\"noselect problemtext\" style=\"display: none;\">" . $problem->text;
            echo "</div>";

            //$link = "index.php?rt=channel/show&id_channel=" . $problem->no;
            //echo "<a href=\"" . $link  . "\">Enter channel: <b>\"" . $problem->title . "\"</b></a>\n";      
            
            if( $ProblemsSolved[$i] )
            {
                echo "Problem solved! (" . $ProblemsSolvedTime[$i] . ")" ;
                echo " <input style=\"transform: scale(2);\" type=\"checkbox\" checked disabled>";
            }
            else
            {
                echo "Problem still not solved!";
            }

            echo "</div>\n";
            echo "<br>\n\n";
            
            $i++;
        }
    ?>

    
    
<?php require_once __DIR__ . '/chat_footer.php'; ?>
