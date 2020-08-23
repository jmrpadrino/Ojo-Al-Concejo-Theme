<?php 
get_header(); 
$contacto = get_page_by_path('contactanos');
?>
<style>
    .btn-secondary {
        border-radius: 25px;
    }
    .address-box {
        padding-top: 50px!important;
        padding-bottom: 30px!important;
    }
</style>
<div class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31918.37576261207!2d-78.49528741251406!3d-0.18743917871377885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d59a7eca7e3e2b%3A0x5f42f2195c570c76!2sConsejo%20Nacional%20Electoral!5e0!3m2!1ses!2sec!4v1597853356219!5m2!1ses!2sec" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
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
                <form>
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
                    <button class="btn btn-secondary" name="contact_send" type="submit">Enviar</button>
                </form>

            </div>
            <div class="col-md-5">
                <div class="address-box w-100 p-4 bg-ececec mt-4 mb-2">
                    <p><strong>Dirección:</strong> Av. Gral. Eloy Alfaro y 6 de Diciembre, Edificio Monasterio Plaza, Piso 10 - Quito.</p>
                    <p><strong>Teléfonos:</strong> (507) 2234120/22/24</p>
                    <p><strong>Correo:</strong> info@ciudadaniaydesarrollo.org</p>
                </div>
                <a href="#" class="btn btn-secondary">¿Eres miembro del Concejo?</a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>