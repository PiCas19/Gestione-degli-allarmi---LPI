<?php
  /**
   * La classe Allarme_Model è il model che mi permette
   * di eseguire delle interrogazioni sulla tabella allarme.
   * @author Pierpaolo Casati
   * @version 19.05.2021
   */
  class Allarme_Model
  {

    function __construct()
    {
    }



   /**
    * Permette di effettuare il reset degli id,
    * in modo che non ci siano dei "buchi" di numerazione.
    * @param conn Connessione al database.
    */
    public function resetIdUser($conn){
      $sth = $conn->prepare("set @num := 0");
      $sth->execute();
      $sth = $conn->prepare("update allarme set id = @num := (@num+1)");
      $sth->execute();
      $sth = $conn->prepare("alter table allarme auto_increment = 1");
      $sth->execute();
    }

    /**
     * Permette di creare un nuovo allarme.
     * @param conn Connessione al database.
     * @param host Host.
     * @param status Stato.
     * @param last_check Ultimo check.
     * @param duration Durata.
     * @param status_information Informazione stato.
     * @param service Servizio.
     */
    public function createAlarm($conn, $host, $status, $last_check, $duration, $status_information, $service){
      $sth = $conn->prepare('insert into allarme (host, servizio, stato, last_check, durata, stato_informazione)
      values(:host, :service, :status, :last_check, :duration, :status_information)');
      $sth->bindParam(':host', $host, PDO::PARAM_STR);
      $sth->bindParam(':service', $service, PDO::PARAM_STR);
      $sth->bindParam(':status', $status, PDO::PARAM_STR);
      $sth->bindParam(':last_check', $last_check, PDO::PARAM_STR);
      $sth->bindParam(':duration', $duration, PDO::PARAM_STR);
      $sth->bindParam(':status_information', $status_information, PDO::PARAM_STR);
      $sth->execute();
    }

    /**
     * Permette di controllare se non ci sia
     * lo stesso allarme con lo stesso ultimo check.
     * @param conn Connessione al database.
     * @param last_check Ultimo check.
     */
    public function checkAlarmLastCheck($conn, $last_check){
      $sth = $conn->prepare('select * from allarme where last_check = :last_check');
      $sth->bindParam(':last_check', $last_check, PDO::PARAM_STR);
      $sth->execute();
      //voglio solo 1 record
		  $result = $sth->fetch(PDO::FETCH_ASSOC);
		  return $result;

    }

    /**
     * Permette di ricavare gli allarmi.
     * @param conn Connessione al database.
     */
    public function getAlarm($conn){
      $sth = $conn->prepare('select * from allarme');
      $sth->execute();
      $result = $sth->fetch(PDO::FETCH_ASSOC);
      return $result;
    }

   /**
    * Permette di controllare se non ci sia
    * lo stesso allarme con lo stesso ultimo check.
    * Filtra anche in base al servizi e allo stato.
    * @param conn Connessione al database.
    * @param last_check Ultimo check.
    * @param host Nome del host.
    * @param service Servizio.
    * @param status Stato del servizio
    */
    public function checkAlarmFilter($conn, $last_check,$host, $service, $status){
      $sth = $conn->prepare('select * from allarme where last_check != :last_check and host = :host and servizio = :service and stato != :status');
      $sth->bindParam(':last_check', $last_check, PDO::PARAM_STR);
      $sth->bindParam(':service', $service, PDO::PARAM_STR);
      $sth->bindParam(':status', $status, PDO::PARAM_STR);
      $sth->bindParam(':host', $host, PDO::PARAM_STR);
      $sth->execute();
      //voglio solo 1 record
      $result = $sth->fetch(PDO::FETCH_ASSOC);
      return $result;
    }

   /**
    * Permette di cambiare lo stato di un determinato check.
    * @param conn Connessione al database.
    * @param value Valore del check.
    * @param field Campo da modificare.
    */
    public function changeCheck($conn, $value, $field){
      $sth = $conn->prepare('update campiCheck set '.$field.' = :value');
      //stato del check
      $state = ($value == "true"?true:false);
      $sth->bindParam(':value', $state, PDO::PARAM_BOOL);
      $sth->execute();
    }

    /**
    * Permette ricavare i vari stati dei vari check.
    * @param conn Connessione al database.
    * @param id Identificativo.
    */
    public function getState($conn){
      $sth = $conn->prepare('select isHost, isStatus,isLastCheck, isDuration, isStatusInformation, isMap from campiCheck');
      $sth->execute();
      $result = $sth->fetch(PDO::FETCH_ASSOC);
      return $result;
    }

    /**
     * Permette di rimuovere un allarme in base al servizio e al nome del host.
     * @param conn Connessione al database.
     * @param host Nome del host.
     * @param service Servizio.
     */
    public function deleteAlarm($conn, $host, $service){
      $sth = $conn->prepare('delete from allarme where host = :host and servizio = :service');
      $sth->bindParam(':host', $host, PDO::PARAM_STR);
      $sth->bindParam(':service', $service, PDO::PARAM_STR);
      $sth->execute();
    }

    /**
     * Permette di aggiornare la durata dell'allarme.
     * @param conn Connessione al database.
     * @param duration Durata.
     */
    public function updateAlarmDuration($conn, $duration){
      $sth = $conn->prepare('update allarme set durata = :duration');
      $sth->bindParam(':duration', $duration, PDO::PARAM_STR);
      $sth->execute();
    }

  }
 ?>
