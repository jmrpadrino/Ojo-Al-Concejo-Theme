<?php
    echo '<h1>404</h1>';
    echo the_title();
    var_dump($_GET);

    echo '<br />' . get_the_permalink();
    echo '<br />';
    echo '<br />';
    echo '<br />';
    do_action('the_posting');
?>