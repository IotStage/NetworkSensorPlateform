<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 22/05/2017
 * Time: 21:54
 */

use App\Controllers\FrontController;
use App\Controllers\APIController;
use Slim\Http\Request;
use Slim\Http\Response;

require './vendor/autoload.php';

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]

]);

require './App/container.php';

$app->get('/', FrontController::class. ':home')->setName("home");
$app->get('/graph', FrontController::class. ':visualisationWithSpecificCapteur')->setName('graph');
$app->get('/page', function (Request $req,  Response $res, $args = []){
    if($req->getParam('page') == "export") $id = 5;
    else $id = 1;
    $url = $this->router->pathFor('graph_tab', ['id' => $id]);
    return $res->withStatus(302)->withHeader('Location', $url);
})->setName('page');
//$app->get('/page', FrontController::class. ':visualisationTab')->setName('Export');
$app->get('/graph/tab/{id}', FrontController::class. ':visualisationTab')->setName('graph_tab');


$app->get('/datas', APIController::class. ':getDatas');
$app->get('/data/{capteur}', APIController::class. ':getDataFromSensor');
$app->get('/data/', APIController::class. ':getSpecificDataFromSensor');
$app->get('/pushDatas/{donnees}', APIController::class. ':pushData');
$app->get('/putCTRL/{donnees}', APIController::class. ':putCTL');
$app->get('/supervisionMsg', APIController::class. ':getSUP');
$app->get('/cmdMsg', APIController::class. ':getCMD');



$app->get('/admin', \App\Controllers\AdminController::class. ':home')->setName("admin");
$app->get('/admin/graph', \App\Controllers\AdminController::class. ':graph')->setName("visualisation");
$app->get('/admin/deconnexion', \App\Controllers\AdminController::class. ':home')->setName('deconnexion');
//$app->get('/admin/msgBidirectionnel', \App\Controllers\AdminController::class. ':bidirectionnel')->setName("msgBidirectionnel");
$app->get('/admin/history', \App\Controllers\AdminController::class. ':getHistorique')->setName("history");
$app->get('/admin/sendCMD', \App\Controllers\AdminController::class. ':sendCMD')->setName("sendCMD");
$app->get('/admin/sendSUP', \App\Controllers\AdminController::class. ':sendSUP')->setName("sendSUP");
$app->get('/admin/receiveCTL', \App\Controllers\AdminController::class. ':receiveCTL')->setName("receiveCTL");
$app->get('/admin/seuil', \App\Controllers\AdminController::class. ':seuil')->setName("seuil");

//$app->get('/', \App\Controllers\AdminController::class. ':home')->setName('deconnexion');


$app->post('/datas', APIController::class. ':addDatas');
$app->post('/data/{capteur}', APIController::class. ':addDataFromSensor');
$app->post('/admin/graph', \App\Controllers\AdminController::class. ':graph')->setName("addGraph");
$app->post('/admin/suppGraph', \App\Controllers\AdminController::class.':suppGraph')->setName("suppGraph");
$app->post('/admin/editGraph',\App\Controllers\AdminController::class. ':editGraph')->setName("editGraph");
$app->post('/admin/sendSUP', \App\Controllers\AdminController::class. ':sendSUP');
$app->post('/admin/sendCMD', \App\Controllers\AdminController::class. ':sendCMD');
$app->post('/admin/seuil', \App\Controllers\AdminController::class. ':seuil')->setName("seuil");
$app->post('/reelTimeDataSensor', FrontController::class. ':reelTimeDataSensor')->setName("reelTimeDataSensor");

$app->get('/alerte', \App\Process\Processus::class. ':process')->setName("alerte");

$app->get('/login', \App\Controllers\BackController::class. ':login')->setName("login");
$app->get('/suscribe', \App\Controllers\BackController::class. ':suscribe')->setName("suscribe");

$app->post('/login', \App\Controllers\BackController::class. ':login')->setName("login");
$app->post('/suscribe', \App\Controllers\BackController::class. ':suscribe')->setName("suscribe");







$app->run();
