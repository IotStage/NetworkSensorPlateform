<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 14:53
 */

namespace App\Controllers;


use App\Constantes;
use App\DataBase\Table\DAO\CommandeDOA;
use App\DataBase\Table\DAO\DataSensorsDAO;
use App\DataBase\Table\DAO\SupervisionDOA;
use App\DataBase\Table\DTO\Commande;
use App\DataBase\Table\DTO\DataSensors;
use App\DataBase\Table\DTO\Supervision;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class APIController extends Controller
{
    public function addDatas(Request $request,Response $response){
        $dataSensordao = DataSensorsDAO::getInstance();
        $dataSensorDTO = new DataSensors();
        $dataSensorDTO->setCapteur($request->getParam('capteur'));
        $dataSensorDTO->setValeur($request->getParam('valeur'));
        $data = $dataSensordao->putData($this->bd, $dataSensorDTO);
        return json_encode($data);
    }

    public function addDataFromSensor(Request $request,Response $response, $arg){
        $capteur = $arg['capteur'];
        $dataSensordao = DataSensorsDAO::getInstance();
        $dataSensorDTO = new DataSensors();
        $dataSensorDTO->setCapteur($capteur);
        $dataSensorDTO->setValeur($request->getParam('valeur'));
        $data = $dataSensordao->putData($this->bd, $dataSensorDTO);
        return $response->withJson($data, 200);
    }

    public function getDatas(RequestInterface $request,ResponseInterface $response){
        $dataSensordao = DataSensorsDAO::getInstance();
        $data = $dataSensordao->getAllDatas($this->bd);
        return $response->withJson($data, 200);
    }

    public function getDataFromSensor(Request $request,Response $response, $arg){
        $capteur = $arg['capteur'];
        $dataSensordao = DataSensorsDAO::getInstance();
        $data = $dataSensordao->getDataFromCapteur($this->bd, $capteur);
        return $response->withJson($data, 200);
    }

    public function getSpecificDataFromSensor(Request $request,Response $response, $arg){
        $capteur = $capteur = $request->getParam('capteur');
        $donnees = DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, $capteur);
        $valeur = array();
        $date = array();
        foreach ($donnees as $key => $value){
            $valeur[$key] = $value["valeur"]+0;
            $date[$key] = $value["date"];
        }
        $data = array(
            $valeur,
            $date
        );
        return $response->withJson($data, 200);
    }

    public function pushDatas(Request $request,Response $response, $arg){
        $data= $arg["donnees"];
        $datas = explode(";",$data);
        $sensor= array(
            "ph","orp", "conductivite_electrique",
            "turbidite","temperature",
            "vitesse", "direction",
            "humidite", "temperature_ambiante",
            "pluie"
        );
        //var_dump($datas);die;
        $i=0;
        foreach ($datas as $key => $value){
            //$sensor = split(":", $value);
            $url = Constantes::$DEFAULT_URL.'/data/'.$sensor[$i]."?valeur=".$value;
            //$donnees = array('capteur' => $sensor[0], 'valeur'=>$sensor[1]);
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                ),
               // 'content' => http_build_query($data)
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $i++;
        }
        echo "merci pour les donnees";

    }
    public function pushData(Request $request,Response $response, $arg){
        $data= $arg["donnees"];
        $datas = explode(";",$data);
        $sensor= array(
            "ph","orp", "conductivite_electrique",
            "turbidite","temperature",
            "vitesse", "direction",
            "humidite", "temperature_ambiante",
            "pluie"
        );
        //var_dump($datas);die;
        $i=0;
        foreach ($datas as $key => $value){
            $dataSensordao = DataSensorsDAO::getInstance();
            $dataSensorDTO = new DataSensors();
            $dataSensorDTO->setCapteur($sensor[$i]);
            $dataSensorDTO->setValeur($value);
            $data = $dataSensordao->putData($this->bd, $dataSensorDTO);
            $i++;
        }
        echo "merci pour les donnees";

    }

    public function putCTL(Request $request,Response $response, $arg)
    {
        # code...
        $data = $arg["donnees"];
        $sup = (new Supervision())
                ->setMessage($data)
                ->setType(Supervision::$TYPE_CMD_SUPERVISON[0]);
        SupervisionDOA::getInstance()->putData($this->bd, $sup);

    }

    public function getSUP(Request $request,Response $response)
    {
        $msg = SupervisionDOA::getLastSupervision($this->bd, Commande::$STATUS_CMD[1]);
        if(isset($msg) && count($msg)>0) {
            echo $msg[0]->message;
            SupervisionDOA::updateStatus($this->bd, Commande::$STATUS_CMD[0], $msg[0]->id);
        }
        else
            echo "";

    }

    public function getCMD(Request $request,Response $response)
    {
        $msg = CommandeDOA::getLastCommande($this->bd);
        if(isset($msg) && $msg[0]->status === Commande::$STATUS_CMD[1]){
                echo $msg[0]->message;
                CommandeDOA::getInstance()->updateStatus($this->bd, Commande::$STATUS_CMD[0], $msg[0]->id);
        }
        else
            echo "";

    }

    public function getHistorique(Request $request,Response $response)
    {
        $historique = array(
            "commande" => CommandeDOA::getInstance()->getAllDatas($this->bd),
            "supervision" => SupervisionDOA::getInstance()->getAllMessageSUP($this->bd),
            "controle" => SupervisionDOA::getInstance()->getAllMessageCTL($this->bd)
        );

        var_dump($historique);
    }

    public function bidirectionnel(Request $request,Response $response)
    {

    }

}