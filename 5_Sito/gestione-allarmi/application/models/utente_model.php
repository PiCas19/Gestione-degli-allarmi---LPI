<?php
  /**
   * La classe Utente_Model è il model che mi permette
   * di eseguire delle interrogazioni sulla tabella utente.
   * @author Pierpaolo Casati
   * @version 10.05.2021
   */
  class Utente_Model
  {

    function __construct()
    {
    }


   /**
    * Permette di leggere tutti i dati dei vari utenti.
    * @param conn Connessione al databse.
    */
    public function getUsers($conn){
      //preparo lo statement che mi selziona tutti gli utenti.
      $sth = $conn->prepare("select * from utente");
      $sth->execute();
      return $sth;
    }

    /**
     * Permette di effettuare il reset degli id,
     * in modo che non ci siano dei "buchi" di numerazione.
     * @param conn Connessione al database.
     */
     public function resetIdUser($conn){
       $sth = $conn->prepare("set @num := 0");
       $sth->execute();
       $sth = $conn->prepare("update utente set id = @num := (@num+1)");
       $sth->execute();
       $sth = $conn->prepare("alter table utente auto_increment = 1");
       $sth->execute();
     }

     /**
      * Permette di creare un nuovo utente.
      * @param conn Connessione al database.
      * @param surname Nome dell'utente.
      * @param lastname Cognome dell'utente.
      * @param email Email dell'utente.
      * @param type Tipo di utente.
      */
     public function createUser($conn, $surname, $lastname, $email, $type, $token){
       $sth = $conn->prepare('insert into utente (email, nome, cognome, tipo, token) values(:email, :surname, :lastname, :type, :token)');
       $sth->bindParam(':email', $email, PDO::PARAM_STR);
       $sth->bindParam(':surname', $surname, PDO::PARAM_STR);
       $sth->bindParam(':lastname', $lastname, PDO::PARAM_STR);
       $sth->bindParam(':type', $type, PDO::PARAM_STR);
       $sth->bindParam(':token', $token, PDO::PARAM_STR);
       $sth->execute();
     }

     /**
      * Permette di modificare l'account di un utente
      * @param conn Connessione al database.
      * @param surname Nome dell'utente.
      * @param lastname Cognome dell'utente.
      * @param email Email dell'utente.
      * @param id Isdentificativo dell'utente.
      */
     public function updateAccount($conn, $surname, $lastname, $email, $id){
       $sth = $conn->prepare("update utente set nome = :surname, cognome = :lastname, email = :email where id = :id");
       $sth->bindParam(':id', $id, PDO::PARAM_INT);
       $sth->bindParam(':email', $email, PDO::PARAM_STR);
       $sth->bindParam(':surname', $surname, PDO::PARAM_STR);
       $sth->bindParam(':lastname', $lastname, PDO::PARAM_STR);
       $sth->execute();

     }

     /**
      * Permette di verificare se esiste già un utente con la stessa email.
      * @param conn Connessione al database.
      * @param email Email dell'utente.
      */
     public function checkEmail($conn, $email){
       $sth = $conn->prepare("select * from utente where email = :email");
       $sth->bindParam(':email', $email, PDO::PARAM_STR);
       $sth->execute();
       //voglio solo 1 record
			 $result = $sth->fetch(PDO::FETCH_ASSOC);
			 return $result;
     }

       /**
      * Permette di eliminare un utente in base all'identificativo.
      * @param conn Connessione al database.
      * @param id Identificativo dell'utente.
      */
     public function deleteUserById($conn, $id){
       $sth = $conn->prepare("delete from utente where id = :id");
       $sth->bindParam(':id', $id, PDO::PARAM_INT);
       $sth->execute();
     }

     /**
      * Permette di verificare che un token esiste.
      * @param conn Connessione al database.
      * @param token Token di un utente.
      */
     public function verifyTokenUser($conn, $token){
        $sth = $conn->prepare("select * from utente where token = :token");
        $sth->bindParam(':token', $token, PDO::PARAM_STR);
        $sth->execute();

        //voglio solo 1 record
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
     }

     /**
      * Permette di modificare la passwrd.
      * @param conn Connessione al database.
      * @param token Token di un utente.
      * @param pswd Password codificata.
      */
     public function updatePasswordUser($conn, $token, $pswd){
        $sth = $conn->prepare("update utente set passwd = :pswd where token = :token");
        $sth->bindParam(':token', $token, PDO::PARAM_STR);
        $sth->bindParam(':pswd', $pswd, PDO::PARAM_STR);
        $sth->execute();
     }

     /**
      * Permette di modificare i permessi di determinato utente.
      * @param conn Connessione al database.
      * @param id Identificativo dell'utente.
      * @param tipo Permessi dell'utente (responsabile e limitato)
      */
     public function updateTypeUser($conn, $id, $tipo){
         $sth = $conn->prepare('update utente set tipo=:type where id=:id');
         $sth->bindParam(':id', $id, PDO::PARAM_INT);
         $sth->bindParam(':type', $tipo, PDO::PARAM_STR);
         $sth->execute();
     }

  }
 ?>
