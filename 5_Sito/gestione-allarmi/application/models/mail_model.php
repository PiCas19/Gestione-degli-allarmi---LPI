<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "./application/sources/PHPMailer-6.4.1/vendor/autoload.php";


/**
 * La classe Mail_Model è il model che mi permette
 * di inviare le email
 * @author Pierpaolo Casati
 * @version 11.05.2021
 */
class Mail_Model
{
    /**
     * Email mittente, default system@gestione-allarmi.ch.
     */
    private $fromMail = "system@gestione-allarmi.ch";

    /**
     * Oggetto PHPMailer.
     */
    private $mail;


    public function __construct()
    {
      //creo un nuovo oggetto PHPMailer
      $this->mail = new PHPMailer(true);
    }

    /**
     * Permette di inviare le email.
     * @param to Indirizzo email del destinatario.
     * @param subject Oggetto dell'email.
     * @param nameRecivement Nome del destinatario (nome e cognome).
     * @param link Link da inviare all'utente
     */
    public function send($to, $subject, $nameRecivement, $link){
        try{    
            //imposto l'indirizzo email e nome mittente
            $this->mail->From = $this->fromMail;
            $this->mail->FromName = "Gestione allarmi";
            
            //imposto l'email destinatario
            $this->mail->addAddress($to, $nameRecivement);
           
            //imposto il soggetto dell'email
            $this->mail->Subject = $subject; 
              
              
            //charset UTF-8
            $this->mail->CharSet = 'UTF-8';
              
            $this->mail->isHTML(true);
                
            //messaggio da inviare
            $message = "
              <html>
                <head>
                  <titleCredenziali account</title>
                </head>
                <body>
                <h3>Benvenuto " . $nameRecivement . " su Gestione allarmi</h3>
                <p>Per modificare la password cliccare il seguente link: <a href='".$link."'>Gestione allarme</a>
                </body>
              </html>
            ";
            //corpo del messaggio
            $this->mail->Body = $message;
            
            //invio l'email
            $this->mail->send();

        }
        catch (Exception $e)
        {
            /* PHPMailer exception. */
            echo $e->errorMessage();
        }
        catch (\Exception $e)
        {
            /* PHP exception (nota la barra rovesciata per selezionare 
            la classe di eccezione dello spazio dei nomi globale). */
            echo $e->getMessage();
        }
    }

}
?>
