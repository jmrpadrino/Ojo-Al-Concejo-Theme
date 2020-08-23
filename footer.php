<footer>
    <div class="container pb-5 pt-5">
        <div class="row">
            <div class="col-md-4">
                Una iniciativa de:&nbsp;&nbsp;&nbsp;<a href="https://www.ciudadaniaydesarrollo.org/"><img class="img-fluid" src="<?php echo THEME_URL . '/img/FCD-isotipo.png'; ?>" alt="Observatorio Logo"></a>
            </div>
            <div class="col-md-4 text-center">
                <ul class="w-100 d-flex justify-content-around list-no-style footer-social-icons">
                    <li><a href="https://www.facebook.com/ciudadaniaydesarrollo/"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="https://twitter.com/FCD_Ecuador"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/fcd_ecuador/"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="https://www.youtube.com/channel/UCCC9Bs3muMobtp46Mo3n-Ew"><i class="fab fa-youtube"></i></a></li>
                </ul>
            </div>
            <div class="col-md-4 text-right">
                Con el apoyo de:&nbsp;&nbsp;&nbsp;<a href="https://www.ned.org/"><img src="<?php echo THEME_URL . '/img/NED-logo.png'; ?>" alt="NED"></a>
            </div>
        </div>
    </div>
</footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo THEME_URL . '/js/jquery.sticky.js'; ?>"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <?php wp_footer(); ?>
    <script>
        $(document).ready(function(){

            $("#fixed-nav").sticky({
                topSpacing:0
            }).on('sticky-end', function() { 
                console.log("Ended"); 
                if(!$('.pre-nav').hasClass('front-page')){
                    $('#fixed-nav-sticky-wrapper').css('height','246px');
                }
                if($('.pre-nav').hasClass('single-page')){
                    console.log('en page');
                    $('#fixed-nav-sticky-wrapper').css('height','184px');
                }
            });
        });
    </script>
</body>
</html>