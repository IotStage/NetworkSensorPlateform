<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 20:08
 */

namespace App\Controllers;


use App\Constantes;
use App\DataBase\Table\DAO\CommandeDOA;
use App\DataBase\Table\DAO\HistoriqueDOA;
use App\DataBase\Table\DAO\MaladieCapteursDOA;
use App\DataBase\Table\DAO\MaladieDOA;
use App\DataBase\Table\DAO\SupervisionDOA;
use App\DataBase\Table\DTO\Commande;
use App\DataBase\Table\DTO\Courbe;
use App\DataBase\Table\DAO\CapteurDOA;
use App\DataBase\Table\DAO\CourbeDOA;
use App\DataBase\Table\DTO\MaladieCapteurs;
use App\DataBase\Table\DTO\Supervision;
use Slim\Http\Request;
use Slim\Http\Response;

session_start();

class AdminController extends Controller
{

    public function home(Request $request, Response $response)
    {
       //% if(isset($this->session['connecte']))
            $this->render($response, 'admin/index.html.twig');
       //% $url = $this->router->pathFor('graph_tab', ['id' => 1]);
       // $response->withStatus(302)->withHeader('Location', $url);
    }

    public function graph(Request $request, Response $response){
        $param = $request->getParams();
        if(count($param)>0) {
            $courbe = (new Courbe())
                        ->setCapteur($param["capteur"])
                        ->setType($param["type"]);
            var_dump(CourbeDOA::getInstance()->putData($this->bd, $courbe));

        }
        $this->render($response, 'admin/graph.html.twig', array(
            'typeCourbe'=>Constantes::getallTypeCourbe(),
            'capteurs' => CapteurDOA::getInstance()->getAllDatas($this->bd),
            'courbes' => CourbeDOA::getInstance()->getAllDatas($this->bd)
        ));

    }

    public function editGraph(Request $request, Response $response){
        $param = $request->getParams();
        if(count($param)>0) {
            $courbe = (new Courbe())
                ->setId($param["id"])
                ->setCapteur($param["capteur"])
                ->setType($param["type"]);
            var_dump(CourbeDOA::getInstance()->editDatas($this->bd, $courbe));

        }
        $this->redirect($response, 'admin/graph.html.twig', array(
            'typeCourbe'=>Constantes::getallTypeCourbe(),
            'capteurs' => CapteurDOA::getInstance()->getAllDatas($this->bd),
            'courbes' => CourbeDOA::getInstance()->getAllDatas($this->bd)
        ));

    }

    public function suppGraph(Request $request, Response $response){
        $param = $request->getParams();
        if(count($param)>0) {
            $courbe = (new Courbe())
                ->setId($param["id"]);
            var_dump(CourbeDOA::getInstance()->deleteDatas($this->bd, $courbe));

        }
        $this->render($response, 'admin/graph.html.twig', array(
            'typeCourbe'=>Constantes::getallTypeCourbe(),
            'capteurs' => CapteurDOA::getInstance()->getAllDatas($this->bd),
            'courbes' => CourbeDOA::getInstance()->getAllDatas($this->bd)
        ));

    }

    public function sendCMD(Request $request, Response $response){
        $add = false;
        $val = $request->getParams();
        if(count($val)>0){
            $cmd = (new Commande())->setMessage($val["delai"]);
            CommandeDOA::getInstance()->putData($this->bd, $cmd);
            $add = true;
        }
        $rep = CommandeDOA::getLastCommande($this->bd)[0];
        return $this->render($response, 'admin/sendCMD.html.twig', array(
            'valeur' => $rep,
            'infos' => $add
        ));
    }

    public function sendSUP(Request $request, Response $response){
        $add = false;
        $val = $request->getParams();
        if(count($val)>0){
            $cmd = (new Supervision())
                ->setMessage($val["message"])
                ->setType(Supervision::$TYPE_CMD_SUPERVISON[1]);
            SupervisionDOA::getInstance()->putData($this->bd, $cmd);
            //var_dump($cmd);die;
            $add = true;
        }
        $rep = array(
            'ctrl' => SupervisionDOA::getLastCTL($this->bd)[0],
            'sup'  => SupervisionDOA::getLastSupervision($this->bd)[0],
        );
        //var_dump($rep);die;
        return $this->render($response, 'admin/sendSUP.html.twig', array(
            'ctl' => $rep,
            'infos' => $add
        ));


    }

    public function getHistorique(Request $request, Response $response){

        return $this->render($response, 'admin/historique.html.twig', array(
            'historique' => HistoriqueDOA::getInstance()->getAllDatas($this->bd),
        ));
    }

    public function seuil(Request $request, Response $response){
        $add = false;
        $val = $request->getParams();
        //var_dump($val);
        if(count($val)>0)
        {
            if(isset($val["maladie"])){
                $mal = (new \App\DataBase\Table\DTO\Maladie())
                    ->setNom($val["nom"])
                    ->setDescription($val["description"]);
                MaladieDOA::getInstance()->putDatas($this->bd, $mal);
            }elseif (isset($val["idMaladie"])){
                $cap = (new MaladieCapteurs())
                    ->setIdMaladie($val["idMaladie"])
                    ->setCapteur($val["capteur"])
                    ->setSeuil($val["seuil"]);
                //var_dump($cap); die;
                MaladieCapteursDOA::getInstance()->putDatas($this->bd, $cap);
            }elseif (isset($val["idEditMaladie"])){
                var_dump($val);
                $cap = (new MaladieCapteurs())
                    ->setId($val["idEditMaladie"])
                    ->setIdMaladie($val["id_Maladie"])
                    ->setCapteur($val["capteur"])
                    ->setSeuil($val["seuil"]);
                //var_dump($cap); die;
                echo MaladieCapteursDOA::getInstance()->editDatas($this->bd, $cap);
            }elseif (isset($val["idDeleteSeuil"])){
                $cap = (new MaladieCapteurs())
                    ->setId($val["idDeleteSeuil"]);
                //var_dump($cap); die;
                MaladieCapteursDOA::getInstance()->deleteDatas($this->bd, $cap);
            }

        }

        $m = MaladieDOA::getInstance()->getAllDatas($this->bd);
        $maladie = MaladieCapteursDOA::getInstance()->getAllDatas($this->bd);
        //var_dump($maladie);die;
        $mala = array();
        foreach ($m as $key => $value)
            $mala[$key] = MaladieCapteursDOA::getAllDataSensorFromSpecificMaladie($this->bd, $value->id);
        return $this->render($response, 'admin/seuil.html.twig', array(
            'maladie' => $m,
            'maladieCapteur'=>$mala,
            'capteurs' => CapteurDOA::getInstance()->getAllDatas($this->bd)
        ));
    }
}