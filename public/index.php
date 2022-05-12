<?php

require '../vendor/autoload.php';

use Dotenv\Dotenv;

/* Con definir aquí las variables de entorno es suficiente, 
 las voy a poder usar en cualquier parte de la app 
*/

/**
 * Objeto de la clase Dotevn
 * Con el podemos usar las variables de entorno
 * 
 */
$dotenv = Dotenv::createImmutable('../');
$dotenv->load();


/**
 * Instancia de la clase Altorouter
 * Se almacenan las rutas de la aplicación
 */
$router = new AltoRouter();
$router->map( 'GET', '/', 'FrontController#home', 'home' );
//$router->map( 'GET|POST', '/home', 'FrontController#begin');
$router->map( 'GET', '/home', 'FrontController#begin');
$router->map( 'POST', '/home', 'FrontController#begin');
$router->map( 'GET', '/access', 'FrontController#access', 'visor' );
$router->map( 'POST', '/access', 'UserController#login', 'user_access' );
$router->map( 'GET', '/register', 'FrontController#register', 'register' );
$router->map( 'POST', '/register', 'UserController#register' );
$router->map( 'GET', '/profile', 'FrontController#profile' ); 
$router->map( 'POST', '/profile/addFavourite', 'UserController#save_favourite' ); 
$router->map( 'GET', '/resetPassword', 'FrontController#reset', 'reset' );
$router->map( 'POST', '/password-reset', 'UserController#passwordReset', 'password-reset' );
$router->map( 'GET', '/contact', 'FrontController#contact', 'contact' );
$router->map( 'POST', '/contact', 'FrontController#mailContact', 'mailContact' );
$router->map( 'GET', '/privacyPolicy', 'FrontController#privacyPolicy', 'privacyPolicy' );
$router->map( 'GET', '/terms&conditions', 'FrontController#termsAndConditions', 'terms-conditions' );
$router->map( 'POST', '/', 'UserController#logOut', 'logout' );
$router->map( 'GET|POST', '/userConfiguration', 'FrontController#userConfiguration', 'control-panel' );
$router->map( 'GET', '/personalInformation/[i:id]', 'FrontController#personalInformation', 'personal-information' );
$router->map( 'POST', '/personalInformation/[i:id]', 'UserController#saveUserInformation', 'personal-information-change' );
$router->map( 'GET', '/changePass', 'FrontController#changePass', 'change-pass' );
$router->map( 'POST', '/changePass/[i:id]', 'UserController#changePass', 'change-pass-save' );
$router->map( 'GET', '/privacyOptions/[i:id]', 'FrontController#privacyOptions', 'privacy-options' );
$router->map( 'GET', '/showFavourites/[i:id_type_element]/[i:id_user]', 'UserController#showFavourites');
$router->map( 'POST', '/showFavourites', 'UserController#deleteFavourite');
$router->map( 'POST', '/deleteUser', 'UserController#deleteAccount');

$match = $router->match();

  if ($match === false) {
      open404Error();
  } else {
      callController($match);
  }
  
  /**
   * open404Error
   * 
   * Muestra una vista determinada cuando una ruta no se encuentra y hay un error 404
   *
   * @return void
   */
  function open404Error() {
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    $controllerObject = new App\Controllers\FrontController;
    $controllerObject->error404();
  }
  
  /**
   * callController
   * 
   * Llama al controlador que debe actuar junto con el método a utilizar
   * Separa la cadena pasada para ver el controlador#método
   * Comprueba si existe el método dentro de la clase
   * Si existe hace una llamada a la método del controlador, pasándole como argumentos los parámetros del objeto match
   * si no encuentra el método invoca a la función para abrir la función de error 404
   *
   * @param  array $match array asociativo de todas las rutas mapeadas
   * @return void
   */
  function callController($match) {
    list( $controller, $action ) = explode( '#', $match['target'] );
      $controller = 'App\\Controllers\\' . $controller;
      if ( method_exists($controller, $action)) {
          $controllerObject = new $controller;
          call_user_func_array(array($controllerObject,$action), $match['params']);
      } else {
          open404Error();
      }
}