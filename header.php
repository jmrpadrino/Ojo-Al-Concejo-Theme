<?php //global $wp_query; ecopre($wp_query); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<title><?php bloginfo('name'); ?> <?php wp_title('|',true,'left'); ?></title>
<link rel="icon" type="image/png" href="<?php echo THEME_URL . '/img/favicon.jpg'; ?>"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="loading-overlay">
    <div class="bounce-loader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>
<?php echo get_template_part('templates/navigation'); ?>