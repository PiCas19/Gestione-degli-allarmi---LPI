
<?php
 /**
  * La classe Passwors_Model è il model che mi
  * permette di gestire le password.
  * @author Pierpaolo Casati
  * @version 12.05.2021
  */
  class Password_Model {

      function __construct()
      {
      }

      /**
       * Permette di verificare che la password
       * passata corrisponde al hash.
       * @param pswd Password in chiaro
       * @param pswd_hash Password codificata.
       */
      public function verify($pswd, $pswd_hash){
          return password_verify($pswd, $pswd_hash);
      }

      /**
       * Permette di codificare la password in hash.
       * @param pswd Password in chiaro.
       */
      public function encode($pswd){
          $hash = password_hash($pswd, PASSWORD_DEFAULT);
          return $hash;
      }


  }


?>
