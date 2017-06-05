<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 12:01
 */

namespace App\Controllers;


use Psr\Http\Message\ResponseInterface;

class Controller
{

    static $EVENT_SAVE_DATA="SAVE_DATA";
    static $EVENT_SEND_CMD="SEND_CMD";
    static $EVENT_SEND_SUP="SEND_SUP";
    static $EVENT_GET_CTL="SEND_CTL";
    static $EVENT_EXECUTE_ALERTE_ALGO="EXECUTE_ALERTE_ALGO";
    static $EVENT_SEND_ALERTE="SEND_ALERTE";
    static $TYPE_EVENT_LOG = "log";
    static $TYPE_EVENT_ERROR="error";


    private $container;

    public function __construct($container)
    {


        $this->container = $container;
    }



    public function render(ResponseInterface $response, $file, $arg = []){
        return $this->container->view->render($response, $file, $arg);
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->container->get($name);
    }

    public function redirect(ResponseInterface $response, $file ,$arg = []){
        return $this->container->view->redirect($response, $file, $arg );
    }


    /**
     * Ecrire dans les fichiers logs
     */
    function writeLog($event, $type_event, $plateforme=""){
        $date_heure = date("Y-m-d H:i:s");
        if($type_event == "error"){
            exec("echo \"$date_heure -> ERROR: $event\" >> /var/log/senpluvio/error.log");
            exec("echo \"$date_heure -> ERROR: $event\" >> /var/log/lampadaire/error.log");
        }
        else if($type_event == "log"){
            exec("echo \"$date_heure -> EVENT: $event\" >> /var/log/$plateforme/$plateforme.log");
        }

    }
}
