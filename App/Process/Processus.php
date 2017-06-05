<?php

/**
 * Created by PhpStorm.
 * User: bng
 * Date: 05/06/2017
 * Time: 00:28
 */

namespace App\Process;
use App\Controllers\Controller;
use App\DataBase\Table\DAO\CapteurDOA;
use App\DataBase\Table\DAO\DataSensorsDAO;
use App\DataBase\Table\DAO\MaladieCapteursDOA;
use App\DataBase\Table\DAO\MaladieDOA;
use Slim\Http\Request;
use Slim\Http\Response;

class Processus extends Controller
{

    public function process(Request $request, Response $response){
        //$capteur = $arg['capteur'];

        $maladie = MaladieDOA::getInstance()->getAllDatas($this->bd);
        foreach ($maladie as $key => $item){
            $capteurs = MaladieCapteursDOA::getAllDataSensorFromSpecificMaladie($this->bd, $item->id);
            foreach ($capteurs as $keycapteur => $itemcapteur){
                $datas = CapteurDOA::getMoyenneFromSpecificCapteur($this->bd, $itemcapteur->capteur)[0];
                $normal = $datas['normale'];
                $seuil = MaladieCapteursDOA::getSeuilFromSpecificSensor($this->bd, $itemcapteur->capteur, $item->nom)[0]['seuil'];
                $value = abs($normal - $seuil);
                if($value > $seuil){
                    echo "\nalerte pour la maladie ".$item->nom.
                        "\nseuil = ".$seuil."\nnormale = ".$normal."\ncapteur = ".$itemcapteur->capteur."\n\n";

                }

                else
                    echo "\nPas d'alerte pour ".$item->nom." avec le capteur ".$itemcapteur->capteur;
            }


        }


    }
}