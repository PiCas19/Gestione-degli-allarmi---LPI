<?php

/**
 * Configurazione
 *
 * Per la configurazione ho utilizzato un template MVC che
 * mi è stato dato durante il modulo 133 (php).
 *
 * Per ulteriori informazioni sulle costanti si prega di @see http://php.net/manual/en/function.define.php
 * Si si vuole sapere perché uso "define" invece di "const" @see http://stackoverflow.com/q/2447791/1114320
 *
 * @author Pierpaolo Casati
 * @version 07.05.2021
 */


/**
 * Configurazione di : Error reporting
 * Utile per vedere tutti i piccoli problemi in fase di sviluppo, in produzione solo quelli gravi
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Configurazione di : URL del progetto
 */
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$dir = str_replace('\\','/',getcwd().'/');
$final = $actual_link.str_replace($documentRoot,'',$dir);
define('URL', $final);


/**
 * Costanti per accedere al database gestione_allarmi
 */
 define('DBNAME', 'gestione_allarmi');
 define('DBUSER', 'root');
 define('DBPASS', '');
 define('DBHOST', 'localhost');
 define('DBPORT', '3306');


 /**
  * Costanti API Nagios
  */
  define('NAGIOS_URL', 'http://monitor.cpt.local/nagios/');
  define('NAGIOS_USER', 'reporter');
  define('NAGIOS_PASS', 'c7Ai-QvU');
  define('NAGIOS_URL_REFRESH', '5');
  define('NAGIOS_TIME', 'Europe/Zurich');
