<?php require_once __DIR__ . '/evaluator_header.php'; ?>

    
    <h2><?php echo $title;?></h2>
    
    <section>
        

        <p>
        Congratulation <?php echo "@" . $username;?>, your solution is correct!
        </p>

        <p>
        You are
            <?php
                $order = [ 1 => "st" , 2 => "nd" , 3 => "rd" ];
                $order_suffix = ( $oder_solved <= 3 ) ? $order[ $oder_solved ] : "th";
                echo $oder_solved . $order_suffix;
            ?>
        member to solve the problem!
        </p>
    </section>

<?php require_once __DIR__ . '/evaluator_footer.php'; ?>
