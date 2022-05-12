<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,  user-scalable=no" />
        <title>RuteaTe</title>
        <!-- fontawesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP"
        crossorigin="anonymous">
        <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>-->
        <script src="/js/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- Leaftlet -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
        <!-- css del sidebar-leaflet -->
        <link rel="stylesheet" href="/assets/css/leaflet-sidebar.css"/>
        <!-- carga de datos abiertos-->
        <script type="text/javascript" src="/geojson/miradores.js"></script>
        <script type="text/javascript" src="/geojson/espacios.js"></script>
        <script type="text/javascript" src="/geojson/sendaline.js"></script>
        <script type="text/javascript" src="/geojson/provinciasCyL.js"></script>
        <script type="text/javascript" src="/geojson/alojamientos.js"></script>
        <script type="text/javascript" src="/geojson/monumentos.js"></script>
        <!--fullscreen-->
        <link rel="stylesheet" href="/assets/css/Control.FullScreen.css"/>
        <script type = "text/javascript" src="/js/Control.FullScreen.js"></script>
        <link rel="stylesheet" href="/assets/css/styles.css">
    </head>
    <body>   
        <nav class="navbar row justify-content-between"> 
            <div class="container ">
                <a class="navbar-brand p-0 ml-5" href="/"><img src="/assets/images/logo_transparente.png" alt="RuteaTe" title="Inicio"></a>
                <?php if(!isset($_SESSION) || empty($_SESSION)):?>
                        <div class="dropdown" id="dropdownVisitor">
                            <button class="btn dropdown-toggle mr-5 px-5" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Menu">
                                <i class="fas fa-user-circle"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="userMenu">
                                <a class="dropdown-item" href="/register" id="menuRegister" role="button">Registro</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/access" id="menuLogin" role="button">Acceder</a>
                            </div>
                        </div>  
                <?php else: ?>  
                        <div class="dropdown" id="dropdownLogin">
                            <button class="btn dropdown-toggle mr-5 px-5" type="button" id="dropdownMenuLoginButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Menu">
                                <i class="fas fa-user-circle registered-user"></i>
                            </button>
                            <div class="dropdown-menu " aria-labelledby="dropdownMenuLoginButton" id="userMenuLogin">
                                <h6 class="dropdown-header" id="userData"><?php echo $_SESSION['name'] . ' ' . $_SESSION['surname'];?></h6>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/userConfiguration" role="button">Panel de control</a>                        
                                <a class="dropdown-item" href="/profile" role="button">Mapa</a>
                                <a class="dropdown-item" href="/home" role="button">Logout</a>
                            </div>
                        </div>
                <?php endif; ?>
            </div>
        </nav>
        <noscript>
            <h1>Bienvenido a Ruteate</h1>
            <p>La página que estás viendo requiere para su funcionamiento el uso de JavaScript.
            Si lo has deshabilitado intencionadamente, por favor vuelve a activarlo.</p>
        </noscript>
        
            <?= $this->section('content') ?>
        
        <footer class="p-2 Footer sticky-bottom">
            
                <div class="row pl-4">
                    <aside class="col-12 col-lg-6 order-lg-1">
                        <ul class="list-unstyled d-flex justify-content-between align-items-center">
                            <li><a href="https://es-es.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a></li>
                            <li><a href="https://twitter.com/?lang=es" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            <li class="pr-lg-2"><a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </aside>
                    <aside class="col-12 col-lg-6 d-flex  justify-content-lg-start pl-lg-5">
                        <ul class="list-unstyled list-statics">
                            <li><a href="/contact">Contacto</a></li>
                            <li><a href="/privacyPolicy" target="_blank">Política de Privacidad</a></li>
                            <li><a href="/terms&conditions" target="_blank">Términos y condiciones</a></li>
                        </ul>
                    </aside>
                </div>
            
        </footer>
        <!-- Bootstrap JS -->
       
        <!--<script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>-->
        <script src="/js/bootstrap.min.js"></script>
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script type = "text/javascript" src="/js/visor.js"></script>
        <!-- sidebar-->
        <script type = "text/javascript" src="/js/leaflet-sidebar.js"></script>
        <!--legend-->
        <!--<script type = "text/javascript" src="/js/leaflet.legend.js"></script>-->
    </body>
</html>