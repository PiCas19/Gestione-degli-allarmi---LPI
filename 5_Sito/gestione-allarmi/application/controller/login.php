<?php

/**
 * La classe Login è il controller principale della pagina di login.
 * @author Pierpaolo Casati
 * @version 07.05.2021
 */
class Login
{

    /**
     * Permette di creare l'index della views login.
     */
    public function index()
    {
      session_start();
      require_once './application/views/_templates/header.php';
      require_once './application/views/login/index.php';
    }

    /**
     * Pwermette di visualizzare la pagina per potere cambiare la password.
     */
    public function changePassword($token){
       //avvio le sessione
        session_start();

        require_once './application/models/utente_model.php';
        require_once './application/models/database_model.php';

        $conn = Database_Model::getConnection();
        $utente_model = new Utente_Model();

        //se il token esiste l'utnte può modificare la password
        if($utente_model->verifyTokenUser($conn, $token)){
          require_once './application/views/_templates/header.php';
          require_once './application/views/login/cambiaPassword.php';
        }
        else{
          //ritorna alla pagina di login.
          header("Location: ".URL);
        }

    }

    /**
     * Permette di modficare la password.
     * @param token Token dell'utente.
     */
    public function modifiyPassword($token){
      //avvio le sessione
      session_start();
      require_once './application/models/utente_model.php';
      require_once './application/models/database_model.php';
      require_once './application/models/password_model.php';

      $conn = Database_Model::getConnection();
      $utente_model = new Utente_Model();
      $password_model = new Password_Model();

      //variabili di errore
      $isErrorPassword = $isErrorConfirmPassword =  false;

      $password = $confirmPassword = "";

      //pattern della password
			$pattern = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/";

      //verifico che il server utilizza il protocoolo HTTP POST
      if($_SERVER["REQUEST_METHOD"] == "POST") {

        //controllo che la password sia stato impostato
        if(isset($_POST['password']) && !empty($_POST['password'])){
          //verifico che la password corrispone al pattern.
          if(preg_match($pattern, $_POST['password'])){
            $password = $this->test_input($_POST['password']);
          }
          else{
              //sessione errore password
              $_SESSION['passwordErr'] = "Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 caratteri.";
              $isErrorPassword = true;
          }
        }
        else{
          //sessione errore password
          $_SESSION['passwordErr'] = "Campo password obbligatorio!";
          $isErrorPassword = true;
        }

        //controllo che la password di conferma sia stato impostato
        if(isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])){
          //verifico che la conferma della password corrisponde alla password.
          if($password === $_POST['confirmPassword']){
            $confirmPassword = $this->test_input($_POST['confirmPassword']);
          }
          else{
              //sessione errore password di conferma
            $_SESSION['confirmPasswordErr'] = "La password di conferma non corrisponde alla password";
            $isErrorConfirmPassword = true;
          }
        }
        else{
            //sessione errore di conferma
            $_SESSION['confirmPasswordErr'] = "Campo conferma password obbligatorio!";
            $isErrorConfirmPassword = true;
        }

        //se non ci sono degli errori modifico la password
        if(!$isErrorPassword && !$isErrorConfirmPassword){
          //codifico la password
          $pswd = $password_model->encode($password);

          //modifico la password.
          $utente_model->updatePasswordUser($conn, $token, $pswd);

          header("Location: ".URL);
        }
        else{
          header("Location: ".URL."login/changePassword/".$token);
        }
      }
    }

    /**
     * Permette di autenticare un utente all'applicativo web.
     */
    public function checkLogin(){
      //avvio le sessione
      session_start();
      require_once './application/models/utente_model.php';
      require_once './application/models/database_model.php';
      require_once './application/models/password_model.php';

      $conn = Database_Model::getConnection();
      $utente_model = new Utente_Model();
      $password_model = new Password_Model();

      //variabili di errore
      $isErrorPassword = $isErrorEmail =  false;

      $password = $email = "";

      //verifico che il server utilizza il protocoolo HTTP POST
      if($_SERVER["REQUEST_METHOD"] == "POST") {
        //controllo che il campo nome sia stato impostato
        if(isset($_POST['password']) && !empty($_POST['password'])){
          $password = $this->test_input($_POST['password']);
        }
        else{
          //sessione errore password
          $_SESSION['passwordErr'] = "Campo password obbligatorio!";
          $isErrorPassword = true;
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
          $isErrorEmail= true;
        }

        //se non ci sono degli errori posso controllare l'autenticazione
        if(!$isErrorEmail && ! $isErrorPassword){
          //risultato della query
          $result = $utente_model->checkEmail($conn, $email);
          //verifico che l'utente esiste in base all'email
          if($result){
              //controllo che la password inserita corrisponde al hash
              if($password_model->verify($password, $result['passwd'])){
                //cero sessione email
                $_SESSION['email'] = $email;
                //cero sessione tipo per i permessi
                $_SESSION['tipo'] = $result['tipo'];

                //identificativo dell'utente
                $_SESSION['id'] = $result['id'];
                
                //vado nella pagina di gestione degli utenti
                header("Location: ".URL."utente");
              }
              else{
                unset(  $_SESSION['emailErr']);
                //sessione errore password
                $_SESSION['passwordErr'] = "Password sbagliata!";
                header("Location: ".URL);
              }
          }
          else{
            unset($_SESSION['passwordErr']);
            //sessione errore email
            $_SESSION['emailErr'] = "Email sbagliata!";
            header("Location: ".URL);
          }
        }
        else{
          header("Location: ".URL);
        }
      }

    }

    /**
     * Permette di visualizzare la views dimentica password,
     * ovvero la pagina dove l'utente deve inserire la propria
     * email per potere modificare la password.
     */
    public function forgotPassword(){
      session_start();
      require_once './application/views/_templates/header.php';
      require_once './application/views/login/passwordDimenticata.php';
    }

    /**
     * Permette di inviare un email per potere cambiare la password.
     */
    public function sendEmailToChangePassword(){
      //avvio le sessione
      session_start();
      require_once './application/models/utente_model.php';
      require_once './application/models/database_model.php';
      require_once './application/models/mail_model.php';

      $conn = Database_Model::getConnection();
      $utente_model = new Utente_Model();
      $mail_model = new Mail_Model();

      //variabili di errore
      $isErrorEmail =  false;

      $result = $email = "";

      //verifico che il server utilizza il protocoolo HTTP POST
      if($_SERVER["REQUEST_METHOD"] == "POST") {
        //se clicco il pulsante Esci ritorno
        //alla pagina di login
        if(isset($_POST['exit'])){
          unset($_SESSION['emailErr']);
          header('Location: '.URL);
        }

        //se clicco il pulsante Richiedi nuova password.
        //viene inviata una nuova email.
        if(isset($_POST['send'])){
          //controllo che il campo email sia stato impostato
          if(isset($_POST['email']) && !empty($_POST['email'])){
            //verifico il campo email
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
              $result = $utente_model->checkEmail($conn, $_POST['email']);
               //se esiste già un email ritorna un errore
               if($result){
                  $email = $this->test_input($_POST['email']);
               }
               else{
                 //sessione errore email
                 $_SESSION['emailErr'] = "Non esiste questo utente.";
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
        }

        //se non ci sono errori viene inviato l'email
        if(!$isErrorEmail){
          //mando l'email
          $mail_model->send($email, "Cambia password", $result['nome'] . " " . $result['cognome'], URL."login/changePassword/".$result['token']);
          header('Location: '.URL."utente");
        }
        else{
          header('Location: '.URL.'login/forgotPassword');
        }
      }

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
