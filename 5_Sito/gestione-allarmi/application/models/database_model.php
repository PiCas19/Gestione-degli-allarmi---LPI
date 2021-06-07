<?php

/**
 * La classe Database_Model è il model che mi
 * permette di accedere al database dell'applicativo web.
 * @author Pierpaolo Casati
 * @version 10.05.2021
 */
class Database_Model
{
  /**
   * Nome del host per accedere al database.
   */
  private static $dbhost = DBHOST;

  /**
	 * Nome del database.
	 */
  private static $dbname = DBNAME;

  /**
   * Porta di ascolto del database.
   */
  private static $dbport = DBPORT;

  /**
	 * Nome dell'utente per accedere al database.
   */
  private static $dbuser = DBUSER;

  /**
	 * Password dell'utente per accedere al database.
	 */
  private static $dbpass = DBPASS;

  /**
	 * Connessione del database
	 */
  private static $conn;


  public function __construct(){

  }

  /**
   * Permette connettersi al database con la classe PDO.
   * @param dbname Nome del database, il valore di default null.
   * @return Connessione al database
   */
  public static function getConnection($dbname = null, $dbuser = null, $dbpass = null){
    try{

      //modifico il nome del database solo se viene passato l'argomento dbname
      if(!is_null($dbname)){
        self::$dbname = $dbname;
      }

      //modifico il nome dell'utente solo se viene passato l'argomento dbuser
      if(!is_null($dbuser)){
        self::$dbuser = $dbuser;
      }

      //modifico la password dell'utente solo se viene passato l'argomento dbpass
      if(!is_null($dbpass)){
        self::$dbpass = $dbpass;
      }

      //permette di creare una sola connessione
      if(!self::$conn){
        $dsn = 'mysql:host='.self::$dbhost.';dbname='.self::$dbname.';port='.self::$dbport;
        self::$conn = new PDO($dsn,self::$dbuser, self::$dbpass);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }

      return self::$conn;

    }
    catch (PDOException $e){
      //messaggio di errore di connessione
      echo $e->getMessage();
    }
  }
}





 ?>
