<?php
namespace App\Models;

use App\Models\Connection;


/**
 * User
 * 
 * Realiza las operaciones CRUD en la base de datos, de las acciones realizadas por el usuario
 * 
 */
class User {
    
    /**
     * connection
     * 
     * Almacena la conexión con la base de datos
     *
     * @var object 
     * @access private
     */
    private $connection;
    
    /**
     * getConnection
     *
     * @return object La conexión con la base de datos
     */
    private function getConnection(){
        return $this->connection = Connection::connection();
    }
    
    /**
     * user_register
     *
     * Guarda en la base de datos los datos introducidos por el usuario
     * 
     * @param  array $data Es un array con los datos del formulario de registro
     * @return true en caso de de que se haya insertado correctamente
     * @return false en caso contrario
     */
    public function user_register($data){

        $ssql = "INSERT INTO users (users.email, users.name, users.surname, users.password, users.register_date, users.user_active, users.reloginDigest, users.username) VALUES (:email, :name, :surname, :password, :register_date, :user_active, :reloginDigest, :username)";
        $prepared = $this->getConnection()->prepare($ssql);
        $isOk = $prepared->execute([
            'email' => $data['email'],
            'name' => ucwords($data['name']) ,
            'surname' => ucwords($data['surname']),
            'password' => password_hash($data['password1'], PASSWORD_DEFAULT),
            'register_date' => date("Y-m-d H:i:s"),
            'user_active' => 1,
            'reloginDigest' => null,
            'username' => ucwords($data['name']) . '-' . ucwords($data['surname']),
        ]);
        if($isOk) {
            return true;
        } else{
            return false;
        }
    }
    
    /**
     * user_access
     *
     * Comprueba en la base de datos que las credenciales para hacer login son correctas
     * 
     * @param  string $email dirección de correo
     * @param  string $password contraseña
     * @return bool true en caso afirmativo y false si las credenciales no son correctas
     */
    public function user_access($email, $password){

        $ok = false;
        $ssql = "SELECT * FROM users WHERE users.email LIKE :email";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute(['email' => $email]);
        $count = $prepared->rowCount();
        if($count > 0){
            $result = $prepared->fetch();
            if($pass = password_verify($password, $result['password'])){
                $ok = true;
            }else{
                $ok = false;
            }
        }else{
            $ok = false;
        }
    
        return $ok;
    }
    
    /**
     * is_exists_email
     * 
     * Comprueba que un email esté en la base de datos
     *
     * @param  string $email dirección de correo
     * @return bool devuelve true en caso de que exista y false en caso de que no se encuentr el email en la base de datos
     */
    public function is_exists_email($email){

        $ssql = "SELECT * FROM users WHERE users.email LIKE :email";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute(['email' => $email]);
        $result = $prepared->rowCount();
        if($result > 0){
            return true;
        }else{
            return false;
        }
    }

    
    
    /**
     * create_cookie
     * 
     * Crea una cookie y actualiza su valor en la base de datos
     *
     * @param  string $email dirección email
     * @return mixed la cookie en caso de que haya actualizado correctamente y false en caso contrario
     */
    public function create_cookie($email){
        
        $name = 'relogin';
        $digest = sha1(strval(rand(0, microtime(true)) . $email . strval(microtime(true))));
        $dataCookie = array(
            'expires' => time() + 60 * 60 * 24,
            'path' => '/',
            'domain' => 'localhost',
            'secure' => false,
            'httponly' => true,
            'sameSite' => 'Strict'
        );
        $cookie = setcookie($name, $digest, $dataCookie);

        $ssql = "UPDATE users SET users.reloginDigest = :reloginDigest WHERE users.email LIKE :email";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute([
            'reloginDigest' => $digest,
            'email' => $email
        ]);

        $count = $prepared->rowCount();
        if($count > 0){
            return $cookie;
        }else{
            return false;
        }
    }
    
    /**
     * relogin
     * 
     * Comprueba que el valor de una cookie coincide con el valor de la base de datos
     *
     * @param  string $digest valor cookie
     * @return mixed un array con los datos del usuario o false si no existe esa cookie en la base de datos
     */
    public function relogin($digest){

        $ssql = "SELECT * FROM users WHERE users.reloginDigest LIKE :reloginDigest";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute([
            'reloginDigest' => $digest,
        ]);

        $count = $prepared->rowCount();
        if($count > 0){
            $result = $prepared->fetch();
            return $result;
        }else{
            return false;
        }
    }
    
    /**
     * initialize_session_vars
     *
     * Inicializa las variables de sesion por usuario 
     * 
     * @param  string $email dirección de correo
     * @return void
     */
    public function initialize_session_vars($email){
        
        $ssql = "SELECT * FROM users WHERE users.email LIKE :email";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute(['email' => $email]);
        $count = $prepared->rowCount();
        if($count > 0){
            $data = $prepared->fetch(\PDO::FETCH_ASSOC);
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['surname'] = $data['surname'];
            //$_SESSION['password'] = $data['password'];
            $_SESSION['register_date'] = $data['register_date'];
            $_SESSION['user_active'] = $data['user_active'];
            $_SESSION['reloginDigest'] = $data['reloginDigest'];
            //añadido despues
            $_SESSION['image'] = $data['image'];
            $_SESSION['about_user'] = $data['about_user'];
            $_SESSION['interests'] = $data['interests'];
            $_SESSION['username'] = $data['username'];

        }
    }
        
    /**
     * save_favourites_user
     * 
     * Inserta un registro a la tabla favoritos 
     *
     * @param  array $data array con los valores a insertar
     * @return bool true en caso de éxito o false en caso de error al insertar
     */
    public function save_favourites_user($data){
        $ssql = "INSERT INTO favourites (id_user, id_type_element, id_element, name, web) VALUES (:id_user, :id_type_element, :id_element, :name, :web)";
        $prepared = $this->getConnection()->prepare($ssql);
        $isOk = $prepared->execute([
            'id_user' => $data['id_user'],
            'id_type_element' => $data['id_type_element'],
            'id_element' => $data['id_element'],
            'name' => $data['name'],
            'web' => $data['web']
        ]);

        if($isOk) {
            return true;
        } else{
            return false;
        }
    }
    
    /**
     * check_favourite
     * 
     * Comprueba si existe un registro en la tabla favoritos
     *
     * @param  array $data un array con el id de usuario y el id del elemento a buscar
     * @return bool true en caso de que exista o false en caso contrario
     */
    public function check_favourite($data){
        $ssql = "SELECT * FROM favourites WHERE id_user= :id_user AND id_element= :id_element";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute([
            'id_user' => $data['id_user'],
            'id_element' => $data['id_element']
        ]);
        
        $count = $prepared->rowCount();
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * remove_favourite
     * 
     * Borra un registro concreto de la tabla favoritos
     *
     * @param  array $data array con los datos a borrar
     * @return bool true en caso de borrarse de manera correcta y false en caso contrario
     */
    public function remove_favourite($data){
        $ssql = "DELETE FROM favourites WHERE id_favourite= :id_favourite";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute([
            'id_favourite' => $data
        ]);

        $count = $prepared->rowCount();
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * show_favourites_by_type
     *
     * Busca los registros de la tabla favoritos por usuario y tipo de elemento
     * 
     * @param  int $id_type_element id del tipo de elemento
     * @param  int $id_user id del usuario
     * @return mixed un array asociativo con los registros encontrados o null en caso de no encontrar ninguno
     */
    public function show_favourites_by_type($id_type_element, $id_user){
        $ssql = "SELECT * FROM favourites WHERE id_type_element= :id_type_element AND id_user= :id_user";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute([
            'id_type_element' => $id_type_element,
            'id_user' => $id_user
        ]);
        $count = $prepared->rowCount();
        if($count > 0){
                $data = $prepared->fetchAll(\PDO::FETCH_ASSOC);
            
            return $data;
        }else{
            return null;
        }
    }
    
    /**
     * user_logout
     * 
     * destruye la sesión del usuario
     *
     * @return void
     */
    public function user_logout(){
        session_start();
        session_destroy();
    }
    
    /**
     * get_user_information
     * 
     * Obtiene información de la base de datos sobre un usuario en concreto
     *
     * @param  int $idUser id del usuario
     * @return mixed un array con los datos del usuario en caso de existir, sino devuelve null
     */
    public function get_user_information($idUser){
        
        $ssql = "SELECT users.name, users.email, users.username, users.about_user, users.interests, users.id_user FROM users WHERE users.id_user LIKE :id_user";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute(['id_user' => $idUser]);
        $count = $prepared->rowCount();
        if($count > 0){
            $data = $prepared->fetch(\PDO::FETCH_ASSOC);
            return $data;
        }else{
            return null;
        }
        
    }
    
    /**
     * update_user_information
     * 
     * Actualiza la información de la base de datos para un usuario en concreto
     *
     * @param  array $data array que contiene los nuevos valores de los campos a actualizar
     * @return bool true en caso de realizarse correctamente y false en caso contrario
     */
    public function update_user_information($data){

        $ssql = "UPDATE users SET name=:name, about_user= :about_user, interests= :interests, username=:username WHERE email= :email";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute([
            'name' => $data['name'],
            'about_user' => $data['about_user'],
            'interests' => $data['interests'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);

        if($prepared->rowCount() > 0){
            
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * checkPass
     * 
     * Selecciona una contraseña de un usuario almacenada en la base de datos y comprueba que es igual a la contraseña introducida por el usuario
     * 
     * @param  int $id id del usuario
     * @param  string $password contraseña introducida por el usuario
     * @return bool true si las contraseñas son iguales y false en caso de ser distintas
     */
    public function checkPass($id, $password){
        $ok = false;
        $ssql = "SELECT password FROM users WHERE id_user= :id_user";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute(['id_user' => $id]);
        $result = $prepared->fetch();
        if($pass = password_verify($password, $result['password'])){
            $ok = true;
        }else{
            $ok = false;
        }
        return $ok;
    }
    
    /**
     * updatePass
     * 
     * Encripta y actualiza el valor de la contraseña en la base de datos para un usuario
     *
     * @param  int $id id del usuario
     * @param  string $pass nueva contraseña
     * @return string mensaje de indormación para el usuario
     */
    public function updatePass($id, $pass){
        
        $ssql = "UPDATE users SET password= :password WHERE id_user=:id_user";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute([
            'password' => password_hash($pass, PASSWORD_DEFAULT),
            'id_user' => $id,
        ]);
        $result = $prepared->rowCount();
        if($result > 0){
            $ok = "Se ha actualizado la contraseña";
        }else{
            $ok =  "Ha ocurrido un problema, inténtelo de nuevo más tarde.";
        }

        return $ok;
    }
    
    /**
     * delete_user_account
     * 
     * Borra un usuario y los datos asociados 
     *
     * @param  int $id id del usuario
     * @return bool true si lo elimina y false en caso contrario
     */
    public function delete_user_account($id){
        $ssql = "DELETE FROM users WHERE id_user= :id_user";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute(['id_user' => $id]);
        $result = $prepared->rowCount();
        if($result > 0){
            $ok = true;
        }else{
            $ok = false;
        }

        return $ok;
    }
    
    /**
     * delete_favourite
     * 
     * Borra un registro concreto de la tabla favoritos
     *
     * @param  int $id_favourite id del registro
     * @return string mensaje de información
     */
    public function delete_favourite($id_favourite){

        $ssql = "DELETE FROM favourites WHERE id_favourite: =id_favourite";
        $prepared = $this->getConnection()->prepare($ssql);
        $prepared->execute(['id_user' => $id_favourite]);
        $result = $prepared->rowCount();
        if($result == 1){
            $ok = true;
        }else{
            $ok =  false;
        }

        return $ok;
    }
}