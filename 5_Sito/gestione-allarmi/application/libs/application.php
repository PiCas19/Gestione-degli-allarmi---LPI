<?php

/**
 * La classe Application contiene le configurazioni dell'applicazione web.
 * Viene diviso l'URL in base a controller, azioni e 3 parametri.
 *
 * @author Pierpaolo Casati
 * @version 07.05.2021
 */
class Application
{
   /**
    * Controller dell'applicazione, valore di default null.
    */
    private $url_controller = null;

   /**
    * Azioni che ci sono dell'applicazione, valore di dafault null.
    */
    private $url_action = null;

   /**
    * Parametro 1 dell'URL, valore di dafault null.
    */
    private $url_parameter_1 = null;

    /**
     * Parametro 2 dell'URL, valore di dafault null.
     */
    private $url_parameter_2 = null;

    /**
     * Parametro 3 dell'URL, valore di dafault null.
     */
    private $url_parameter_3 = null;

    public function __construct()
    {
       //funzione da creare per dividere l'URL
        $this->splitUrl();
        if (file_exists('./application/controller/' . $this->url_controller . '.php')) {
            require './application/controller/' . $this->url_controller . '.php';
            $this->url_controller = new $this->url_controller();
            //se esistono dei metodi o delle azioni (quando mi sposto nelle pagine web o eseguo delle azioni)
            if (method_exists($this->url_controller, $this->url_action)) {
                if (isset($this->url_parameter_3)) {
                    $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2,
                        $this->url_parameter_3);
                } elseif (isset($this->url_parameter_2)) {
                    $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2);
                } elseif (isset($this->url_parameter_1)) {
                    $this->url_controller->{$this->url_action}($this->url_parameter_1);
                } else {
                    $this->url_controller->{$this->url_action}();
                }
            } else {
                $this->url_controller->index();
            }
        } else {
            //index principale dell'applicativo web
            require './application/controller/login.php';
            $home = new Login();
            $home->index();
        }
    }

    /**
     * Splitto l'url URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // tolgo il carattere / dalla fine della stringa
            $url = rtrim($_GET['url'], '/');
            //rimuove tutti i caratteri illegali dall'URL
            $url = filter_var($url, FILTER_SANITIZE_URL);
            //divido in un array in base al carattere /
            $url = explode('/', $url);

            // divido le parti dell'utl in base a controller, azione e 3 parametri
            $this->url_controller = (isset($url[0]) ? $url[0] : null);
            $this->url_action = (isset($url[1]) ? $url[1] : null);
            $this->url_parameter_1 = (isset($url[2]) ? $url[2] : null);
            $this->url_parameter_2 = (isset($url[3]) ? $url[3] : null);
            $this->url_parameter_3 = (isset($url[4]) ? $url[4] : null);

            // Per debug
            // echo 'Controller: ' . $this->url_controller . '<br />';
            // echo 'Action: ' . $this->url_action . '<br />';
            // echo 'Parameter 1: ' . $this->url_parameter_1 . '<br />';
            // echo 'Parameter 2: ' . $this->url_parameter_2 . '<br />';
            // echo 'Parameter 3: ' . $this->url_parameter_3 . '<br />';
        }
    }
}
