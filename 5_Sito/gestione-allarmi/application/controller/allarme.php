<?php

/**
 * La classe Allarme è il controller principale per gestire e visualizzare gli allarmi.
 * @author Pierpaolo Casati
 * @version 17.05.2021
 */
class Allarme
{

    /**
     * Permette di creare l'index della views Allarme
     */
    public function index()
    {

      //inizializzo le sessioni
      session_start();

      //se non è stato eseguito un login si ritorna nella pagina di login
      if(!isset($_SESSION['email'])){
        header("Location: ".URL);
      }

      $stats = $this->createArrayAllData();

      require_once './application/models/database_model.php';
      require_once './application/models/allarme_model.php';

      //connessione al database gestione_allarmi
      $conn = Database_Model::getConnection();


      $allarme_model = new Allarme_Model();

      $row = $allarme_model->getState($conn);

      require_once './application/views/_templates/header.php';
      require_once './application/views/_templates/navbar.php';
      require_once './application/views/allarme/index.php';
      require_once './application/views/_templates/footer.php';
    }

    public function display(){
       //inizializzo le sessioni
       session_start();

       //se non è stato eseguito un login si ritorna nella pagina di login
       if(!isset($_SESSION['email'])){
         header("Location: ".URL);
       }

        $this->connect_API("cgi-bin/statusjson.cgi");
        require_once './application/views/_templates/header.php';
        require_once './application/views/_templates/navbar.php';
        require_once './application/views/allarme/schermo.php';
        require_once './application/views/_templates/footer.php';
    }

    /**
     * Permette di creare l'allarme.
     */
    public function createAlarm(){

      require_once './application/models/database_model.php';
      require_once './application/models/allarme_model.php';

      //connessione al database gestione_allarmi
      $conn = Database_Model::getConnection();


      $allarme_model = new Allarme_Model();

      //faccio il reset l'id
      $allarme_model->resetIdUser($conn);

      //stato
      $status = $_POST['status'];


      //servizi critici
      $servicesCritical = $this->get_data("cgi-bin/statusjson.cgi?query=servicelist&details=true&servicestatus=".$status);

      //tutti i servizi
      $services = $this->get_data("cgi-bin/statusjson.cgi?query=servicelist&details=true");

      //ciclo i servizi critici
      foreach($servicesCritical['data']['servicelist'] as $key => $value){
        foreach ($value as $v) {
            //ultimo check.
            $last_check =  date('Y-m-d H:i:s', $v['last_check']/1000);
            //non ci possono essere degli allarmi con lo stesso ultimo check.
            if(!$allarme_model->checkAlarmLastCheck($conn,$last_check)){
              //creo l'allarme
              $allarme_model->createAlarm(
                $conn,
                $v['host_name'],
                $v['status'],
                date('Y-m-d H:i:s', $v['last_check']/1000),
                $this->calculateDuration($v['last_state_change']/1000),
                $v['plugin_output'],
                $v['description']
              );
            }
            else{
              //modifico la durata.
              $allarme_model->updateAlarmDuration(
                $conn,
                $this->calculateDuration($v['last_state_change']/1000)
              );
            }
        }
      }


      foreach($services['data']['servicelist'] as $key => $value){
        foreach ($value as $v) {
          //ultimo check.
          $last_check =  date('Y-m-d H:i:s', $v['last_check']/1000);

          //controllo se esiste l'allarme in base al nome, last_check, servizio e stato
          $result = $allarme_model->checkAlarmFilter(
            $conn,
            $last_check,
            $v['host_name'],
            $v['description'],
            $v['status']
          );

          if($result){
            //elimino l'allarme
            $allarme_model->deleteAlarm(
              $conn,
              $result['host'],
              $result['servizio']
            );
          }

        }
      }

      echo json_encode($allarme_model->getState($conn));
    }

    /**
     * Permette di modificare gli stati dei vari check.
     */
    public function changeCheck(){
        $check = $_POST['check'];
        $index = $_POST['index'];

        require_once './application/models/database_model.php';
        require_once './application/models/allarme_model.php';

        //connessione al database gestione_allarmi
        $conn = Database_Model::getConnection();


        $allarme_model = new Allarme_Model();

        $allarme_model->changeCheck($conn, $check, $index);

    }

    /**
     * Permette di ricavare i dati dalla tabella allarme.
     */
    public function loadAlarm(){
        require_once './application/models/database_model.php';
        require_once './application/models/allarme_model.php';

        //connessione al database gestione_allarmi
        $conn = Database_Model::getConnection();


        $allarme_model = new Allarme_Model();
        //ricavo i dati dalla tabella allarme.
        $dati = $allarme_model->getAlarm($conn);
        if($dati){
           echo json_encode($dati);
        }
        else{
          echo null;
        }
    }

    /**
     * Permette ottenere i dati dal modulo Nagios tramite l'API JSON.
     */
    public function get_data($get_url){
      //connessione all'API NAGIOS
      $get_status = curl_init(NAGIOS_URL . $get_url);
      curl_setopt($get_status, CURLOPT_RETURNTRANSFER, true);
      //imposto la password e lo username
      curl_setopt($get_status, CURLOPT_USERPWD, NAGIOS_USER . ":" . NAGIOS_PASS);
      //eseguo il curl per ricavare le risorse
      $res = curl_exec($get_status);
      //chiudo la connessione
      curl_close($get_status);
      return json_decode($res, true);
    }


    /**
     * Permette di connettersi all'API.
     */
    public function connect_API($get_url){
      //connessione all'API NAGIOS
      $get_status = curl_init(NAGIOS_URL . $get_url);
      curl_setopt($get_status, CURLOPT_RETURNTRANSFER, true);
      //imposto la password e lo username
      curl_setopt($get_status, CURLOPT_USERPWD, NAGIOS_USER . ":" . NAGIOS_PASS);
      //eseguo il curl
      curl_exec($get_status);
    }

    /**
     * Permette di creare un array che contiene tutte le informazioni.
     */
    public function createArrayAllData(){
        $arrayData = array();
        $count = 0;
        //lista dei host
        $hostlist  = $this->get_data("cgi-bin/statusjson.cgi?query=servicelist&details=true");
        //permette di cicliare la lista dei vari elementi
        foreach ($hostlist['data']['servicelist'] as $key => $value){
          //permette di ciclare i vari servizi
          foreach($value as $v){
            $arrayData[$count] = array(
              "hostname"=> $v['host_name'],
              "description" => $v['description'],
              "status" => $v['status'],
              "last_check"=>date('d.m.Y H:i:s', $v['last_check']/1000),
              "duration"=>$this->calculateDuration($v['last_state_change']/1000),
              "status_information" => $v['plugin_output']

            );
            $count++;

          }
        }

        return $arrayData;
    }

    /**
     * Permette di calcolare la durata che corrisponde legal
     * tra l'ultimo cambiamento di stato e la data corrente.
     * @param last_state_change Ultimo cambiamento di stato.
     */
    private function calculateDuration($last_state_change){
        //orario e data dell'ultimo cambiamento di stato
        $time1 = date_create(date('d.m.Y H:i:s', $last_state_change));
        //orario e data corrente
        $time2 = date_create(date('d.m.Y H:i:s'));

        //differenze tra i due orari
        $time_diff = $time1->diff($time2);

        return $time_diff->d .' d ' . $time_diff->h.' h ' . $time_diff->i.' m ' .  $time_diff->s.' s';
    }



}
?>
