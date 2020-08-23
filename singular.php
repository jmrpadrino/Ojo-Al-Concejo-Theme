<?php
    echo '<h1>En el singular</h1>';
    echo the_title();

    echo '<br />' . get_the_permalink();
    echo '<br />';
    echo '<br />';
    echo '<br />';
    do_action('the_posting');
?>