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
use App\DataBase\Table\DAO\MaladieCapteursDOA;
use App\Vue\Graphique;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

session_start();

class FrontController extends Controller
{


    static $PAGE = array(
        null,
        'Pages/Temps_reel.html.twig',
        'Pages/visualisation_globale.html.twig',
        'Pages/visualisation_seuil.html.twig',
        'Pages/combinate.html.twig',
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
    public function visualisationTab(Request $request, Response $response, $arg){
        $graph = array();
        //$all = array();
        if(!isset($arg)) {

            if ($request->getParam('page') == "visualisation") {
                $this->render($response, FrontController::$PAGE[1], array('graph' => null));
            } else if ($request->getParam('page') == "export") {
                $this->render($response, FrontController::$PAGE[4], array('graph' => null));
            }
        }
        $graphique= array();
        if($arg["id"] == 1){
            $courbe = CourbeDOA::getInstance()->getAllDatas($this->bd);
            foreach ($courbe as $key => $value){
                $graphique[$key]=array(
                    'title' => "variation ".$value->capteur,
                    'id'    => $key,
                    'type'    => $value->type_courbe,
                    'nom'   => $value->capteur,
                    'unite' => $value->unite,
                    //'datas'  => DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd,$value->capteur ),
                );
            }
            $this->render($response, FrontController::$PAGE[$arg["id"]], array('graph' => $graphique));
        }elseif ($arg["id"] == 2){
            $courbe = CourbeDOA::getInstance()->getAllDatas($this->bd);
            foreach ($courbe as $key => $value){
                $graphique[$key]=array(
                    'title' => "variation du ".$value->capteur,
                    'id'    => $key,
                    'type'  => $value->type_courbe,
                    'nom'   => $value->capteur,
                    'datas' => DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd,$value->capteur ),
                );
            }
            $this->render($response, FrontController::$PAGE[$arg["id"]], array('graph' => $graphique));
        }elseif ($arg["id"] == 4){
            $grah=array();
            $grah1 = DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, "temperature_ambiante");
            $grah2 = DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, "temperature");
            $grah[] = array(
                "nom"=> "conductivite electrique",
                "unite"=> "ms/mm",
                "data"=> DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, "conductivite_electrique")
            );
            $grah[] = array(
                "nom"=> "turbidite",
                "unite"=>"Volt",
                "data"=> DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, "turbidite")
            );
            $grah[] = array(
                "nom"=> "ph",
                "unite"=> "",
                "data"=> DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, "ph")
            );

            $wind=array();
            $wind["direction"]=DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, "direction");
            $wind["vitesse"]=DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, "vitesse");

            $this->render($response, FrontController::$PAGE[$arg["id"]], array(
                'ambiante' => $grah1,
                'eau'=>$grah2,
                'graph'=> $grah,
                'wind' => $wind
            ));
        }elseif ($arg["id"] == 3){
            $date = DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd, 'orp');

            $capteur=array();
            $datas = MaladieCapteursDOA::getInstance()->getAllDatas($this->bd);
            $maladie=$datas[0]->nom;
            $donnees=array();
            $id=$datas[0]->id_maladie;
            foreach ($datas as $key => $value) {
                //if($key == 0)
                 //   $id = $value->id_maladie;
                //if($value->nom == $maladie){
                    $capteur[] = array(
                        'nom'=> $value->capteur,
                        'seuil'=>$value->seuil,
                        'datas'=>DataSensorsDAO::getInstance()->getDataFromCapteur($this->bd, $value->capteur)
                    );
                //}
                /*else{
                    //if(count($capteur)>0){
                        $donnees[]=array('maladie'=> $maladie, 'valeurs'=> $capteur);
                        $capteur=array();
                       // var_dump(array($key, $capteur));//  echo count($capteur);
                        //$maladie = $value->nom;
                    //}
                    $maladie = $value->nom;
                    //$id = $value->id_maladie;
                }
*/
            }
            //return $response->withJson($capteur, 200);
            //var_dump(json_encode($donnees));die;
           $this->render($response, FrontController::$PAGE[$arg["id"]], array('datas' => $capteur));
        }

        //var_dump($graphique);
        //$this->render($response, FrontController::$PAGE[$arg["id"]], array('graph' => null));


    }


    public function reelTimeDataSensor(Request $request, Response $response){
        $param = $request->getParams();
        $capteur = $param["sensor"];
        return json_encode(DataSensorsDAO::getInstance()->getLastDataSpecificCapteur($this->bd, $capteur));
        //var_dump(DataSensorsDAO::getInstance()->getSpecificDataFromCapteur($this->bd,"ph"));
    }
    public function exportation(Request $request, Response $response){
        if(count($request->getParams())>0){
            $output="";
            $param = $request->getParams();
            $nbcolonne = count($param) - 1;
            $allDatas= array();
            $capteurs=array();
            $i=0;
            $entete="";
            $dataSensordao = DataSensorsDAO::getInstance();
            foreach ($param as $key => $value) {
                if($key!="format"){
                 //$allDatas[$key] = $dataSensordao->getSpecificDataFromCapteur($this->bd, $key);
                    $capteurs["capteur".$i]=$key;
                    $entete.="<th>".ucfirst($key)."</th>";
                }
                $i++;
            }
            $datas=$dataSensordao->getDataFromManyCapteur($this->bd,$capteurs);
            $output .= '<table class="table" bordered="1">';
            $output .= "<thead>
                     <tr>  
                          $entete  
                         <th>Date</th>  
                        <th>Heure</th>   
                     </tr>  
                </thead>
                <tbody>
                    <tr>
                ";  
            $x=0;

            foreach ($datas as $key => $value) {
                foreach ($param as $key2 => $value2) {
                    if($value["capteur"] === $key2)
                        $output.="<td>".$value["valeur"]."</td>";
                }
                  $x++;

                if($x==$nbcolonne){
                    $output.="<td>".(explode(" ",$value["date"])[0])."</td>";
                    $output.="<td>".(explode(" ",$value["date"])[1])."</td>";
                    
                    if($key != count($datas)-1)
                        $output.='</tr><tr>';
                    else 
                        $output.='</tr>';
                    $x=0;
                }

              
            }
            $output .= '</tbody></table>';  
            header("Content-Type: application/xls");   
            header("Content-Disposition: attachment; filename=download.xls"); 
           echo $output;
           
        }

        else
        $this->render($response, FrontController::$PAGE[5]);

    }






}
