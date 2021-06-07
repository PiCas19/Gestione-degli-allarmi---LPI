<?php

/**
 * La classe Utente è il controller principale della pagina gestione utenti.
 * @author Pierpaolo Casati
 * @version 07.05.2021
 */
class Utente
{

    /**
     * Permette di creare l'index della views utente
     */
    public function index()
    {

      //inizializzo le sessioni
      session_start();

      //se non è stato eseguito un login si ritorna nella pagina di login
      if(!isset($_SESSION['email'])){
        header("Location: ".URL);
      }

      require_once './application/models/database_model.php';
      require_once './application/models/utente_model.php';

      //connessione al database gestione_allarmi
      $conn = Database_Model::getConnection();


      $utente_model = new Utente_Model();
      //eseguo il reset degli id
      $utenti = $utente_model->resetIdUser($conn);
      //ricavo tutti i dati della tabella utenti
      $utenti = $utente_model->getUsers($conn);

      require_once './application/views/_templates/header.php';
      require_once './application/views/_templates/navbar.php';
      require_once './application/views/utente/index.php';
      require_once './application/views/_templates/footer.php';
    }

    /**
     * Permette di accedere alla pagina
     * dove si può creare un nuovo utente.
     */
    public function viewAddUser(){

        //avvio le sessione
        session_start();



        //se non è stato eseguito un login si ritorna nella pagina di login
        if(!isset($_SESSION['email'])){
          header("Location: ".URL);
        }

        require_once './application/views/_templates/header.php';
        require_once './application/views/utente/aggiungi.php';
        require_once './application/views/_templates/footer.php';
    }

    /**
     * Permette di aggiornare i dati dell'account.
     */
    public function checkUpdateUsers(){
      //avvio le sessione
      session_start();

      require_once './application/models/utente_model.php';
      require_once './application/models/database_model.php';

      $conn = Database_Model::getConnection();
      $utente_model = new Utente_Model();

      //variabili di errore
      $isErrorName = $isErrorLastName = $isErrorEmail  = false;

      //espressione regolare per il campo nome e cognome
      $pattern = "/^[A-Z]+[a-z]{0,}/";

      $surname = $lastname = $email =  "";
      //verifico che il server utilizza il protocoolo HTTP POST
      if($_SERVER["REQUEST_METHOD"] == "POST") {
        //se clicco il pulsante Esci ritorno
        //alla pagina principale degli utenti
        if(isset($_POST['exit'])){
          unset($_SESSION['nameErr']);
          unset($_SESSION['surnameErr']);
          unset($_SESSION['emailErr']);
          header('Location: '.URL."utente");
        }
        //se clicco il pulsante Crea aggiungo un nuovo utente.
        if(isset($_POST['create'])){
          //controllo che il campo nome sia stato impostato
          if(isset($_POST['surname']) && !empty($_POST['surname'])){
            //verifico il campo nome
            if(preg_match($pattern, $_POST['surname'])){
              $surname = $this->test_input($_POST['surname']);
            }
            else{
              //sessione errore nome
              $_SESSION['nameErr'] = "La prima lettera deve essere maiuscole e le altre minuscole";
              $isErrorName = true;
            }
          }
          else{
            //sessione errore nome
            $_SESSION['nameErr'] = "Campo nome obbligatorio!";
            $isErrorName = true;
          }

          //controllo che il campo cognome sia stato impostato
          if(isset($_POST['lastname']) && !empty($_POST['lastname'])){
            //verifico il campo cognome
            if(preg_match($pattern, $_POST['lastname'])){
              $lastname = $this->test_input($_POST['lastname']);
            }
            else{
              //sessione errore cognome
              $_SESSION['surnameErr'] = "La prima lettera deve essere maiuscole e le altre minuscole";
              $isErrorLastName = true;
            }
          }
          else{
            //sessione errore cognome
            $_SESSION['surnameErr'] = "Campo cognome obbligatorio!";
            $isErrorLastName = true;
          }

          //controllo che il campo email sia stato impostato
          if(isset($_POST['email']) && !empty($_POST['email'])){
            //verifico il campo email
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
               $email = $this->test_input($_POST['email']);
            }
            else{
              //sessione errore email
              $_SESSION['emailErr'] = "Formato email sbagliato.";
              $isErrorEmail = true;
            }
          }
          else{
            //sessione errore email
            $_SESSION['emailErr'] = "Campo email obbligatorio!";
            $isErrorEmail = true;
          }
          //se non ci sono degli errori viene creato il nuovo utente
          if(!$isErrorName && !$isErrorLastName && !$isErrorEmail){
            $utente_model->updateAccount($conn, $surname, $lastname, $email, $_SESSION['id']);
            header('Location: '.URL."utente");
          }
          else{
            $this->viewAccount();
          }
        }
      }
    }


  /**
  * Permette di controllare i valori inseriti
  * nei campi e di creare un nuovo utente.
  */
  public function checkAddUsers(){
    //avvio le sessione
    session_start();

    require_once './application/models/utente_model.php';
    require_once './application/models/database_model.php';
    require_once './application/models/mail_model.php';

    $conn = Database_Model::getConnection();
    $utente_model = new Utente_Model();
    $mail_model = new Mail_Model();

    //variabili di errore
    $isErrorName = $isErrorLastName = $isErrorEmail = $isErrorType = false;

    //espressione regolare per il campo nome e cognome
    $pattern = "/^[A-Z]+[a-z]{0,}/";

    $surname = $lastname = $email =  $type = $token = "";
    //verifico che il server utilizza il protocoolo HTTP POST
    if($_SERVER["REQUEST_METHOD"] == "POST") {
      //se clicco il pulsante Esci ritorno
      //alla pagina principale degli utenti
      if(isset($_POST['exit'])){
        unset($_SESSION['nameErr']);
        unset($_SESSION['surnameErr']);
        unset($_SESSION['emailErr']);
        unset($_SESSION['typeErr']);
        header('Location: '.URL."utente");
      }

      //se clicco il pulsante Crea aggiungo un nuovo utente.
      if(isset($_POST['create'])){
        //controllo che il campo nome sia stato impostato
        if(isset($_POST['surname']) && !empty($_POST['surname'])){
          //verifico il campo nome
          if(preg_match($pattern, $_POST['surname'])){
            $surname = $this->test_input($_POST['surname']);
          }
          else{
            //sessione errore nome
            $_SESSION['nameErr'] = "La prima lettera deve essere maiuscole e le altre minuscole";
            $isErrorName = true;
          }
        }
        else{
          //sessione errore nome
          $_SESSION['nameErr'] = "Campo nome obbligatorio!";
          $isErrorName = true;
        }

        //controllo che il campo cognome sia stato impostato
        if(isset($_POST['lastname']) && !empty($_POST['lastname'])){
          //verifico il campo cognome
          if(preg_match($pattern, $_POST['lastname'])){
            $lastname = $this->test_input($_POST['lastname']);
          }
          else{
            //sessione errore cognome
            $_SESSION['surnameErr'] = "La prima lettera deve essere maiuscole e le altre minuscole";
            $isErrorLastName = true;
          }
        }
        else{
          //sessione errore cognome
          $_SESSION['surnameErr'] = "Campo cognome obbligatorio!";
          $isErrorLastName = true;
        }

        //controllo che il campo email sia stato impostato
        if(isset($_POST['email']) && !empty($_POST['email'])){
          //verifico il campo email
          if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
             $email = $this->test_input($_POST['email']);
             //se esiste già un email ritorna un errore
             if($utente_model->checkEmail($conn, $email)){
               //sessione errore email
               $_SESSION['emailErr'] = "Esiste già l'utente.";
               $isErrorEmail = true;
             }
          }
          else{
            //sessione errore email
            $_SESSION['emailErr'] = "Formato email sbagliato.";
            $isErrorEmail = true;
          }
        }
        else{
          //sessione errore email
          $_SESSION['emailErr'] = "Campo email obbligatorio!";
          $isErrorEmail = true;
        }

        //controllo che il campo type sia stato impostato
        if(isset($_POST['type']) && !empty($_POST['type'])){
          //verifico il campo tipo di utente
          if($_POST['type'] == 'amministratore' ||
             $_POST['type'] == 'limitato'){
               $type = $this->test_input($_POST['type']);
          }
          else{
            //sessione errore tipo di accesso
            $_SESSION['typeErr'] = "Un utente può essere amministratore o limitato.";
            $isErrorType = true;
          }
        }
        else{
          //sessione errore tipo di accesso
          $_SESSION['typeErr'] = "Campo tipo di utente obbligatorio!";
          $isErrorEmail = true;
        }

        //se non ci sono degli errori viene creato il nuovo utente
        if(!$isErrorName && !$isErrorLastName && !$isErrorType && !$isErrorEmail){
          $token = $this->createToken();
          $utente_model->createUser($conn, $surname, $lastname, $email, $type, $token);
          $mail_model->send($email, "Credenziali account", $surname . " " . $lastname, URL."login/changePassword/".$token);
          header('Location: '.URL."utente");
        }
        else{
          $this->viewAddUser();
        }
      }
    }
  }

  /**
   * Permette di visualizzare i dati dell'account.
   */
  public function viewAccount(){
    session_start();
    //se non è stato eseguito un login si ritorna nella pagina di login
    if(!isset($_SESSION['email'])){
      header("Location: ".URL);
    }

    require_once './application/models/database_model.php';
    require_once './application/models/utente_model.php';

    //connessione al database gestione_allarmi
    $conn = Database_Model::getConnection();


    $utente_model = new Utente_Model();

    $row = $utente_model->checkEmail($conn, $_SESSION['email']);

    require_once './application/views/_templates/header.php';
    require_once './application/views/utente/modifica.php';
    require_once './application/views/_templates/footer.php';

  }

  /**
   * Permette di eliminare un determinato utente.
   * @param id Identificativo dell'utente.
   */
  public function deleteUserById($id){
    require_once './application/models/utente_model.php';
    require_once './application/models/database_model.php';

    $conn = Database_Model::getConnection();
    $utente_model = new Utente_Model();

    //elimino l'utente
    $utente_model->deleteUserById($conn,$id);

    header("Location: ".URL."utente");
  }

  /**
   * Permette di modificare i permessi dell'utente.
   */
  public function changeType(){
    require './application/models/database_model.php';
    require './application/models/utente_model.php';

    $id =  $this->test_input($_POST["id"]);
    $type = $this->test_input($_POST["value"]);
    $conn = Database_Model::getConnection();
    $utente_model = new Utente_Model();

    //modifico i permessi dell'utente
    $utente_model->updateTypeUser($conn, $id,$type);
    header("Location: ".URL."utente");
  }


  /**
   * Permette di uscire dall'applicativo web.
   */
  public function logout(){
    session_start();
    //distruggo tutte le sessioni
    session_destroy();
    header("Location: ".URL);
    exit;
  }


  /**
   * Permette di creare il token
   */
  private function createToken(){
    $token = bin2hex(random_bytes(20));
    return hash("sha256", $token);
  }

  /**
   * Permette di controllare il dato inserito sia corretto
   * @param data Valore inserito nel campo.
   */
  private function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
  }
}


 ?>
