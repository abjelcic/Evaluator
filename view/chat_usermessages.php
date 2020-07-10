<?php require_once __DIR__ . '/chat_header.php'; ?>

    
    <h2><?php echo $title?></h2>

    <?php
        foreach( $MessagesList as $message )
        {
            echo "<div class=\"message\">";
            
            echo "<h3>" . $message->sender->name . "</h3> ";
            
            echo "<div class=\"date\">" . $message->date . "</div>\n";
            
            echo "<div class=\"content\">\n";
                echo "<p>" . $message->content . "</p>\n";
                echo "<div class=\"thumbsup\"> Likes: " . $message->thumbs_up . "</div>\n";
                $link = "index.php?rt=channel/show&id_channel=" . $message->id_channel;
                echo "<a href=\"" . $link  . "\">Enter channel: <b>\"" . $message->channelName() . "\"</b></a>\n";
            echo "</div>\n";
            
            echo "</div>";
            echo "<br>\n\n";
        }
    ?>


<?php require_once __DIR__ . '/chat_footer.php'; ?>
