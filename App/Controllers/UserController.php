<?php
namespace App\Controllers;

use App\Models\User;
use League\Plates\Engine;

/**
 * UserController
 */
class UserController extends Controller {
        
    /**
     * userModel
     * 
     * Contiene un objeto de la clase User
     *
     * @var mixed
     */
    private $userModel;
        
    /**
     * register
     * 
     * Recibe por post los datos del registro de usuario
     * Filtra y sanitiza los valores
     * valida los datos
     * comprueba si existe el email en la base de datos
     * Si existe renderiza la vista correspondiente con los datos pasados
     * Si el email no existe, registra al usuario
     * si el registro es correcto inicia sesión y las variables de sesión y envía un email al usuario
     * y renderiza la vista correspondiente
     *
     * @return void
     */
    public function register(){
        $errors = [];
        $data = [];
        if(isset($_POST) && !empty($_POST)){
            
            $email = trim($_POST['email']);
            $data['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
            $name = trim($_POST['name']);
            $data['name'] = filter_var($name, FILTER_SANITIZE_STRING);
            $surname = trim($_POST['surname']);
            $data['surname'] = filter_var($surname, FILTER_SANITIZE_STRING);
            $password1 = trim($_POST['password1']);
            $data['password1'] = filter_var($password1, FILTER_SANITIZE_STRING);
            $password2 = trim($_POST['password2']);
            $data['password2'] = filter_var($password2, FILTER_SANITIZE_STRING);
            $errors = $this->validate($data);
            $this->userModel = new User;
            if($this->userModel->is_exists_email($data['email']) != true){
                if(count($errors) === 0){ 
                    $result = $this->userModel->user_register($data);
                    if($result === true){
                        session_start();
                        $this->userModel->initialize_session_vars($data['email']);
                        mail($data['email'], "Mensaje de bienvenida", "Gracias por registrarte en Ruteate");
                        $message = 'Registrado correctamente';
                        echo $this->templates->render('sections/profile',['message' => $message]);
                    }else{
                        $errors['final'] = 'Ha ocurrido un problema, por favor regístrese de nuevo más tarde';
                        echo $this->templates->render('sections/register',['errors' => $errors]);
                    }
                }else{
                    
                    echo $this->templates->render('sections/register',['errors' => $errors]); 
                }
            }else{
                $errors['duplicated'] = "El email ya está registrado, por favor utilice uno diferente";
                echo $this->templates->render('sections/register',['errors' => $errors]);
            }
        }
    }
    
    /**
     * validate
     * 
     * Valida unos campos de acuerso a unas normas
     *
     * @param  array $data datos a validar
     * @return array con los errores 
     */
    private function validate($data){
        $errors = [];
        if(!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'El email debe tener un formato correcto';
        }

        if(strlen($_POST['password1']) < 6 || strlen($_POST['password1']) > 12){
            $errors['way'] = 'La contraseña debe tener entre 6 y 12 caracteres';
        }

        if($_POST['password1'] !== $_POST['password2']){
            $errors['same'] = 'Las contraseñas no coinciden';
        }

        return $errors;
    }
    
    /**
     * login
     * 
     * Recoge los datos por post
     * Filtra y sanitiza los datos
     * ejecuta la función de acceso del usuario y si es true
     * evalua el campo remember y si exist y tiene un valor crea una cookie
     * incia a sesión y las variables de sesisón
     * y muestra la vista profile
     * si es false renderiza la vista correspondiente
     *
     * @return void
     */
    public function login(){
        $this->userModel = new User;
        $data = [];
        if(isset($_POST) && !empty($_POST)){
            $email = trim($_POST['email']);
            $data['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
            $password= trim($_POST['password']);
            $data['password'] = filter_var($password, FILTER_SANITIZE_STRING);
            
            if($this->userModel->user_access($data['email'], $data['password'])){
                if(isset($_POST['remember']) && $_POST['remember'] == "1"){
                    $this->userModel->create_cookie($data['email']);
                }
                session_start();
                $this->userModel->initialize_session_vars($data['email']);
                echo $this->templates->render('sections/profile');
                

            }else{
                $error = 'Usuario y contraseña incorrectos, inténtelo de nuevo'; 
                echo $this->templates->render('sections/access',['error' => $error]);
            }
            
        }
    }
    
    /**
     * passwordReset
     * 
     * Recoge un valor email por post
     * si existe envia un email
     * renderiza la vista correspondiente
     *
     * @return void
     */
    public function passwordReset(){
        $this->userModel = new User;
        if(isset($_POST['email']) && !empty($_POST['email'])){
            if($this->userModel->is_exists_email($_POST['email']) !== true){
                $error = "No existe ningún usuario con ese email, introduce un email válido.";
                echo $this->templates->render('sections/resetPassword',['error' => $error]);
            }else{
                $userEmail = trim($_POST['email']);
                $userEmail = filter_var($userEmail, FILTER_SANITIZE_EMAIL);
                $message = "Pulsa en este enlace para poder recuperar la contraseña.";
                if(mail($userEmail, "Recuperación de contraseña", $message)){
                    $success = "Te hemos enviado un correo, visualizalo para poder acceder.";
                    echo $this->templates->render('sections/resetPassword', ['success' => $success]);
                }else{
                    $error = "No hemos podido recibir su correo electrónico. Por favor, inténtelo de nuevo más tarde.";
                    echo $this->templates->render('sections/resetPassword',['error' => $error]);
                }
            }
        }
    }
    
    /**
     * logOut
     *
     * Ejecuta la función para el log out del usuario y redirecciona al incio de la aplicación
     * 
     * @return void
     */
    public function logOut(){
        $this->userModel = new User;
        $this->userModel->user_logout();
        header('location: /home.php');
    }
    
    /**
     * saveUserInformation
     * 
     * Recibe datos por post
     * los filtra y sanitiza
     * para actualizar la informción del usuario 
     * Recoge los nuevos valores y se los pasa a la vista para que los renderice
     *
     * @param  int $id id del usuario
     * @return void
     */
    public function saveUserInformation($id){
        $this->userModel = new User;
        if(isset($_POST) && !empty($_POST)){
            if($_POST['form2'] == 'form2'){
                $data['name'] = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
                $data['username'] = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);  
                $data['about_user'] = filter_var(trim($_POST['about_user']), FILTER_SANITIZE_STRING);
                $data['interests'] = filter_var(trim($_POST['interests']), FILTER_SANITIZE_STRING);
                $data['email'] = $_POST['email'];
                

                if($this->userModel->update_user_information($data)){
                    $this->userModel->initialize_session_vars($data['email']);
                    $newData = $this->userModel->get_user_information($id);
                    echo $this->templates->render('sections/personalInformation', ['data' => $newData]);
                }
            }

        }
    }
       
    /**
     * save_favourite
     * 
     * Recibe por ajax y el metodo post el elemento favorito a guardar
     * Comprueba que ese elemento no este guardado ya para ese usuario
     * y si no lo esta lo guarda
     * despues codifica el array con los datos response
     *
     * @return void
     */
    public function save_favourite(){

        header("Content-type: application/json; charset=utf-8");
        if(isset($_POST) && !empty($_POST)){

            $data = $_POST;
            $this->userModel = new User;
            $exists = $this->userModel->check_favourite($data);
            if($exists === false){
                $saveFavourite = $this->userModel->save_favourites_user($data);
                if($saveFavourite){
                    $result['favourite'] = 1; 
                }else{
                    $result['favourite'] = 0; 
                }
            }else{
                $result['favourite'] = 2; 
            }
            
            
            echo json_encode($result);
        }
    }
    
    /**
     * changePass
     * 
     * Recibe datos por post, los filtra y sanitiza
     * Comprueba que la contrseña antigua introducida coincide con el valor almacenado en la base de datos
     * Si coinciden los valores y pasan de nuevo un filtro se añade el nuevo valor a la base de datos
     * En todos los casos se renderiza una vista con un mensaje determinado
     *
     * @param  int $id id de usuario
     * @return void
     */
    public function changePass($id){
        $this->userModel = new User;
        
        if(isset($_POST) && !empty($_POST)){
            
            $oldPass = filter_var(trim($_POST['oldPassword']), FILTER_SANITIZE_STRING);
            $newPass = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);
            $result = $this->userModel->checkPass($id, $oldPass);
            if($result){
                if(strlen($newPass) >= 6 && strlen($newPass) <= 12){
                    $message = $this->userModel->updatePass($id, $newPass);
                    echo $this->templates->render('sections/changePass', ['message' => $message]);

                }else{
                    $message = "La contraseña debe tener entre 6 y 12 caracteres.";
                    echo $this->templates->render('sections/changePass', ['message' => $message]);
                }
            }else{
                $message = "La contraseña antigua no coincide.";
                echo $this->templates->render('sections/changePass', ['message' => $message]);
            }
            
        }
       
    }
    
    /**
     * showFavourites
     * 
     * Muestra los elementos favoritos de un determinado tipo para un determinado usuario
     *
     * @param  int $id_type_element
     * @param  int $id_user
     * @return void
     */
    public function showFavourites($id_type_element, $id_user){
        
        $this->userModel = new User;
        $data = $this->userModel->show_favourites_by_type($id_type_element, $id_user);
        if($data === null){
            $result['message'] = "Aún no tienes datos guardados";
            echo $this->templates->render('sections/showFavourites', ['result' => $result]);
        }else{
            echo $this->templates->render('sections/showFavourites', ['data' => $data]);
        }
    }

        
    /**
     * deleteFavourite
     * 
     * Recoge por post un id de favorito
     * y borra el registro con ese id de los favoritos
     *
     * @return void
     */
    public function deleteFavourite(){

       
        if(isset($_POST) && !empty($_POST)){
            $id = (int)$_POST['favourite'];
            $this->userModel = new User;
            $ok = $this->userModel->remove_favourite($id);
            if($ok){
                $favourite['favourite'] = true;
            }else{
                $favourite['favourite'] = false;
            }
        }
       
       echo $this->templates->render('sections/userConfiguration');
    }
    
    /**
     * deleteAccount
     * 
     * Recoge un id por post
     * Y borra la cuenta de usuario para ese id
     * destruye la sesión
     * y redirecciona al inicio
     *
     * @return void
     */
    public function deleteAccount(){
        if(isset($_POST) && !empty($_POST)){
            $id_user = $_POST['user'];
            $id =intval($id_user);
            $this->userModel = new User;
            $ok = $this->userModel->delete_user_account($id);
            if($ok){
                $this->userModel->user_logout();
                header("Refresh:0; url=home");
            }
        }
    }
}