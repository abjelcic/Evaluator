<?php require_once __DIR__ . '/chat_header.php'; ?>

    <h2><?php echo $title?></h2>
    
    <div class="channel">
    <form action="index.php?rt=channel/createnew" method="post">
        <h3>New channel's name: </h3>
        
        <p>
        <input type="text" name="NewChannelName">
        <button type="submit">Create new channel!</button>
        </p>

    </form>
    </div>

<?php require_once __DIR__ . '/chat_footer.php'; ?>



