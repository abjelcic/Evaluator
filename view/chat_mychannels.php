<?php require_once __DIR__ . '/chat_header.php'; ?>

    
    <h2><?php echo $title;?></h2>
    
    <?php
        foreach( $ChannelsList as $channel )
        {
            echo "<div class=\"channel\">\n";
            
            echo "<h3>" . $channel->title . "</h3> ";
            
            $link = "index.php?rt=channel/show&id_channel=" . $channel->id;
            echo "<a href=\"" . $link  . "\">Enter channel: <b>\"" . $channel->title . "\"</b></a>\n";      
                        
            echo "</div>\n";
            echo "<br>\n\n";
   
        }
    ?>


<?php require_once __DIR__ . '/chat_footer.php'; ?>
