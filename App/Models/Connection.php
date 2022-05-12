<?php
namespace App\Models;

/**
 * Connection
 * 
 * Realiza la conexión con la base de datos
 * 
 */
class Connection {
  
  /**
   * connection
   * 
   * Realiza la conexión con la base de datos, usando para ello los valores almacenados en las variables de entorno
   *
   * @return object la conexión
   */
  public static function connection(){
          $dsn = "mysql:dbname={$_ENV['DB_NAME']};host={$_ENV['DB_HOST'] }";
      try {
          $connection = new \PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
      } catch (PDOException $e) {
          echo 'Falló la conexión: ' . $e->getMessage();
      }
      return $connection;
  }
  
}