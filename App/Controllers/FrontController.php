<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 12:03
 */

namespace App\Controllers;


use App\Constantes;
use App\DataBase\Table\DAO\CourbeDOA;
use App\DataBase\Table\DAO\DataSensorsDAO;
use App\Vue\Graphique;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;

class FrontController extends Controller
{


    static $PAGE = array(
        null, 'Pages/Temps_reel.html.twig',
        'Pages/visualisation_globale.html.twig',
        'Pages/parametre_maladies.html.twig',
        'Pages/exportation.html.twig'
    );

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function home(RequestInterface $request, ResponseInterface $response){
        //return $response->getBody()->write("bonjour et ça marche");
       return $this->render($response, 'Pages/home.html.twig');
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function visualisation(RequestInterface $request, ResponseInterface $response){

        $graphique = Graphique::getGraphWithMinimalConfiguration('ph');

        $donnees = DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, 'ph');
        //var_dump($donnees);
        $valeur = array();
        $date = array();
        foreach ($donnees as $key => $value){
            $valeur[$key] = $value["valeur"]+0;
            $date[$key] = $value["date"];

        }
        $graphique->setSeries(
            array(
                'name' => "ma premiere courbe",
                'data' => $valeur
            ));
        $graph = $graphique->getGraph();
		return $this->render($response, 'Pages/visualisation.html.twig', array('graph' => $graph));
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function visualisationWithSpecificCapteur(Request $request, ResponseInterface $response){
        //$capteur = $request->getParam('capteur');
        $param = $request->getParams();
        $capteur = $param["capteur"];
        //var_dump($param);
        $graphique = Graphique::getGraphWithMinimalConfiguration($capteur);

        //var_dump($result);
        $graphique->setType('area');
        $serie = array();
        foreach ($param as $key => $val){
            $capteur = $val;
            $url =Constantes::$DEFAULT_URL.'/data/?capteur='.$capteur;
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'GET',
                ),
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $result = json_decode($result);
            $serie[]=array(
                'name' => $capteur,
                'data' => $result[0]
            );
        }

        $graphique->setSeries($serie);
        //var_dump($serie);
        $graph = $graphique->getGraph();
        return $this->render($response, 'Pages/visualisation.html.twig', array('graph' => $graph));
    }


    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $arg
     */
    public function visualisationTab(RequestInterface $request, ResponseInterface $response, $arg){
        $graph = array();
        //$all = array();
        $graphique= array();
        if($arg["id"] == 1){
            $courbe = CourbeDOA::getInstance()->getAllDatas($this->bd);
            foreach ($courbe as $key => $value){
                $graphique[$key]=array(
                    'title' => "test graphique",
                    'id'    => $key,
                    'type'    => $value->type,
                    'nom'   => $value->capteur,
                    'datas'  => DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd,$value->capteur ),
                );
            }
            $this->render($response, FrontController::$PAGE[$arg["id"]], array('graph' => $graphique));
        }
        //var_dump($graphique);
        $this->render($response, FrontController::$PAGE[$arg["id"]], array('graph' => null));


    }



}
