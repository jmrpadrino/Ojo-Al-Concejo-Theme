<?php 
get_header(); 
$contacto = get_page_by_path('contactanos');
$mensaje = '';
if (isset($_POST['contact_send'])){
    ob_start();
?>
<h1>Contenido del mensaje</h1>
<p><strong>Nombre:</strong> <?php echo $_POST['nombres']; ?></p>
<p><strong>E-mail:</strong> <?php echo $_POST['email']; ?></p>
<p><strong>Asunto:</strong> <?php echo $_POST['asunto']; ?></p>
<p><strong>Mensaje:</strong> <?php echo $_POST['mensaje']; ?></p>
<?php
    $html = ob_get_clean();
    $to = 'info@ojoalconcejo.org';
    $subject = 'Tiene un mensaje nuevo de Ojo al Concejo';
    $body = $html;
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: Ojo al Concejo <info@ojoalconcejo.org>';
    $headers[] = 'Reply-To: No Reply <noreply@ojoalconcejo.org>';
    $mensaje = '<span class="text-danger bold">Su mensaje no se ha podido enviar.</span>';
    if( wp_mail( $to, $subject, '', $headers ) ){
        unset($_POST);
        $mensaje = '<span class="text-success bold">Su mensaje ha sido enviado con éxito.</span>';
    };
}
?>
<style>
    .btn-secondary.contact-btn {
        border-radius: 25px;
        background-color: #4A4F55;
    }
    .address-box {
        padding-top: 50px!important;
        padding-bottom: 30px!important;
    }
</style>
<div class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.797116451607!2d-78.48195628572998!3d-0.1867966354664069!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d59a7ebea4f481%3A0xfa8f226c19a6dee9!2sMonasterio%20Plaza%2C%20Av.%20Eloy%20Alfaro%202129%2C%20Quito%20170135!5e0!3m2!1ses!2sec!4v1598393722636!5m2!1ses!2sec" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
</div>
<section id="contact-info">
    <div class="container mt-4 mb-4">
        <div class="row mb-3">
            <div class="col-sm-12 text-center">
                <h2 class="fs-36 bold"><?php echo $contacto->post_title; ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 offset-md-1">
                <p>Envíanos un <strong>mensaje</strong></p>
                <form role="form" method="post">
                    <style scoped>
                        ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
                            font-style: italic;
                        }
                        ::-moz-placeholder { /* Firefox 19+ */
                            font-style: italic;
                        }
                        :-ms-input-placeholder { /* IE 10+ */
                            font-style: italic;
                        }
                        :-moz-placeholder { /* Firefox 18- */
                            font-style: italic;
                        }
                        .form-control {
                            border-radius: 0;
                        }
                    </style>
                    <input type="text" class="form-control mb-1 bg-ececec" name="nombres" placeholder="Nombres y Apellidos" required>
                    <input type="email" class="form-control mb-1 bg-ececec" name="email" placeholder="E-mail" required>
                    <input type="text" class="form-control mb-1 bg-ececec" name="asunto" placeholder="Asunto" required>
                    <textarea class="form-control mb-1 bg-ececec" name="mensaje" id="" rows="2" placeholder="Mensaje"></textarea>
                    <?php 
                    if (!empty($mensaje)){ 
                        echo '<p>' . $mensaje . '</p>';
                    }
                    ?>
                    <button class="btn btn-secondary contact-btn" name="contact_send" type="submit">Enviar</button>
                </form>

            </div>
            <div class="col-md-5">
                <div class="address-box w-100 p-4 bg-ececec mt-4 mb-2">
                    <p><strong>Dirección:</strong> Av. Gral. Eloy Alfaro y 6 de Diciembre, Edificio Monasterio Plaza, Piso 10 - Quito.</p>
                    <p><strong>Teléfonos:</strong> (507) 2234120/22/24</p>
                    <p><strong>Correo:</strong> info@ciudadaniaydesarrollo.org<br /><span style="margin-left: 63px;">info@ojoalconcejo.org</span></p>
                    
                </div>
                <a href="#" class="btn btn-secondary contact-btn">¿Eres miembro del Concejo?</a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>