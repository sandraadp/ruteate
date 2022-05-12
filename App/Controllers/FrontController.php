<?php
namespace App\Controllers;

use League\Plates\Engine;
use App\Models\User;

/**
 * FrontController
 * 
 * Controla las vistas que han de mostrarse para usuarios no registrados y alguna para los registrados
 */
class FrontController extends Controller {

    /**
     * home
     * 
     * Comprueba si exite una cookie (relogin) y una cookie de sesión para inciar la sesion y las variables de sesión finalmente nos muestra la página de inicio
     *
     * @return void
     */
    public function home() {
        $this->userModel = new User;
        if(isset($_COOKIE['relogin']) && $_COOKIE['relogin'] !== null){
            $digest = $_COOKIE['relogin'];
            if($result = $this->userModel->relogin($digest)){
                session_start();
                $this->userModel->initialize_session_vars($result['email']);
            }
        }
        if(isset($_COOKIE["PHPSESSID"])){ //Si lo hago de esta manera nunca puede ver la home, para que funcione bien tengo que cambiar el menu si existe la sesion
            session_start();
        }
        echo $this->templates->render('sections/home');
    }
    
    /**
     * error404
     * 
     * Muestra la vista para los errores 404
     *
     * @return void
     */
    public function error404() {
        echo $this->templates->render('sections/404');
    }
    
    /**
     * access
     * 
     * Muestra la vista para la ruta access
     *
     * @return void
     */
    public function access(){
        echo $this->templates->render('sections/access');
    }
    
    /**
     * register
     * 
     * Muestra la vista para la ruta resgister
     *
     * @return void
     */
    public function register(){
        echo $this->templates->render('sections/register');
    }
    
    /**
     * reset
     * 
     * Muestra la vista para la ruta resetPassword
     *
     * @return void
     */
    public function reset(){
        echo $this->templates->render('sections/resetPassword');
    }

        
    /**
     * profile
     * 
     * Comprueba el método de envío sea get, en caso afirmativo inicia la sesión e inicializa las variables de sesión para un usuario concreto
     * Siempre muestra la vista para la ruta profile 
     * 
     * @return void
     */
    public function profile(){
        
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            session_start();
            $this->userModel = new User;
            $this->userModel->initialize_session_vars($_SESSION['email']);
        }
        echo $this->templates->render('sections/profile');

    }
    
    
    /**
     * contact
     * 
     * Muestra la vista para la ruta contact
     *
     * @return void
     */
    public function contact(){
        echo $this->templates->render('sections/contact');

    }
    
    
    /**
     * privacyPolicy
     *
     * Inicia una sesión y sus variables al abrir en una nueva pestaña la politica de privacidad
     * 
     * @return void
     */
    public function privacyPolicy(){
        $this->userModel = new User;
        if(isset($_COOKIE['relogin']) && $_COOKIE['relogin'] !== null){
            $digest = $_COOKIE['relogin'];
            if($result = $this->userModel->relogin($digest)){
                session_start();
                $this->userModel->initialize_session_vars($result['email']);
            }
        }
        if(isset($_COOKIE["PHPSESSID"])){ //Si lo hago de esta manera nunca puede ver la home, para que funcione bien tengo que cambiar el menu si existe la sesion
            session_start();
        }
        echo $this->templates->render('sections/privacyPolicy');

    }
    
    /**
     * termsAndconditions
     * 
     * Inicia una sesión y sus variables al abrir en una nueva pestaña los términos y condiciones
     *
     * @return void
     */
    public function termsAndconditions(){
        $this->userModel = new User;
        if(isset($_COOKIE['relogin']) && $_COOKIE['relogin'] !== null){
            $digest = $_COOKIE['relogin'];
            if($result = $this->userModel->relogin($digest)){
                session_start();
                $this->userModel->initialize_session_vars($result['email']);
            }
        }
        if(isset($_COOKIE["PHPSESSID"])){ //Si lo hago de esta manera nunca puede ver la home, para que funcione bien tengo que cambiar el menu si existe la sesion
            session_start();
        }
        echo $this->templates->render('sections/terms&conditions');
    }

        
    /**
     * userConfiguration
     * 
     * Comprueba si existe un error
     * Si existe renderiza la vista de la información personal
     * Si no existe renderiza el menu de panel de control del usuario
     *
     * @return void
     */
    public function userConfiguration(){
        if(isset($error)){
            echo $this->templates->render('sections/personalInformation', ['error' => $error]);

        }else{
            echo $this->templates->render('sections/userConfiguration');
        }
        
    }

       
    /**
     * personalInformation
     * 
     * Obtiene información de la base de datos de un usuario
     * Si la información no es null, le pasa un array con la información del usuario a la vista
     * Si existe un mesaje se lo pasa a la vista
     * y finalmente renderiza la vista correspondiente, con los datos necesarios en caso de necesitarlos
     *
     * @param  int $id id del usuario para el que se quiere buscar la indformación
     * @return void
     */
    public function personalInformation($id){
        //session_start();
        $this->userModel = new User;
        if($this->userModel->get_user_information($id) !== null){
            $data = $this->userModel->get_user_information($id);
            echo $this->templates->render('sections/personalInformation', ['data' => $data]);
        }else if(isset($message)){
            echo $this->templates->render('sections/personalInformation', ['error' => $message, 'data' => $data]);

        }else{
            echo $this->templates->render('sections/personalInformation');

        }
        

    }
     
    
    /**
     * mailContact
     * 
     * Recoge los datos vía post
     * Los filtra y sanitiza
     * Y envía con esos datos y una cabecera específica un correo
     * Despues renderiza una vista con un mensaje determinado
     *
     * @return void
     */
    public function mailContact(){
        $data = [];
        if(isset($_POST) && !empty($_POST)){
        $name = trim($_POST['name']);
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $visitor_email = trim($_POST['email']);
        $visitor_email = filter_var($visitor_email, FILTER_SANITIZE_EMAIL);
        $visitorMessagge = trim($_POST['messagge']);
        $visitorMessagge = filter_var($visitorMessagge, FILTER_SANITIZE_STRING);
        $visitorMessagge = htmlspecialchars($visitorMessagge);
        $headers  = 'MIME-Version: 1.0' . "\r\n"
        .'Content-type: text/html; charset=utf-8' . "\r\n"
        .'From: ' . $visitor_email . "\r\n";
        if(mail("contact@ruteate.es", "Mensaje de contacto", $visitorMessagge, $headers)){
            $success = "Gracias por su mensaje, le reponderemos lo antes posible.";
            echo $this->templates->render('sections/contact',['success' => $success]);
        }else{
            $error = "Lo sentimos, pero el correo electrónico no fue recibido.";
            echo $this->templates->render('sections/contact',['error' => $error]);
        }
        
        }else{
        $error = "Algo salió mal";
        
        }
    }
    
    /**
     * begin
     * 
     * Muestra la vista para la ruta home
     *
     * @return void
     */
    public function begin(){

        echo $this->templates->render('sections/home');
    }
    
    /**
     * changePass
     * 
     * Muestra la vista para la ruta changePass
     *
     * @return void
     */
    public function changePass(){

        echo $this->templates->render('sections/changePass');        
    }
    
    /**
     * changePassMessage
     * 
     * Muestra la vista de la ruta changePass pasandole como parámetro $message
     *
     * @param  string $message 
     * @return void
     */
    public function changePassMessage($message){

        echo $this->templates->render('sections/changePass', ['message' => $message]);
    }
    
    /**
     * privacyOptions
     * 
     * Muestra las opciones de privacidad pasandole como parámetro el id del usuario
     *
     * @param  int $id id del usuario
     * @return void
     */
    public function privacyOptions($id){

        echo $this->templates->render('sections/privacyOptions');
    }
    
    /**
     * showFavourites
     * 
     * Muestra la vista de la ruta showFavourites
     *
     * @return void
     */
    public function showFavourites(){
        
        echo $this->templates->render('sections/showFavourites');
    }
}