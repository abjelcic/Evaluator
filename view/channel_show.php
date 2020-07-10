<?php require_once __DIR__ . '/chat_header.php'; ?>

    
    <h2><?php echo 'Channel: ' . $title?></h2>

    <?php
        foreach( $MessagesList as $message )
        {
            echo "<div class=\"message\">";
            
            echo "<h3>" . $message->sender->name . "</h3> ";
            
            echo "<div class=\"date\">" . $message->date . "</div>\n";
            
            echo "<div class=\"content\">\n";
                echo "<p>" . $message->content . "</p>\n";
                $LikeRoute = "index.php?rt=message/like&id_message=" . $message->id;
                echo "<a id=\"thumbsup\" href=\"" . $LikeRoute . "\"> Likes: " . $message->thumbs_up . "</a>\n";
            echo "</div>\n";
            
            echo "</div>";
            echo "<br>\n\n";
        }
    ?>

    <div class="input_wrap">
    <form method="post" action=<?php echo "index.php?rt=channel/post&id_channel=" . $id_channel;?> >
        <p>Write to <b><?php echo $title;?></b>:</p>
        <textarea name="content"></textarea>
        <button type="submit">Submit</button>
    </form>
    <div class="input_wrap">
    

<?php require_once __DIR__ . '/chat_footer.php'; ?>
