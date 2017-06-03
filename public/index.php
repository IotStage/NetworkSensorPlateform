

<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 22/05/2017
 * Time: 21:54
 */
require '../vendor/autoload.php';

//$bd = App\DataBase\DataBase::getPDO();
//echo "get instence acheve";


$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]

]);

require '../App/container.php';

$app->get('/', \App\Controllers\FrontController::class. ':home');

$app->run();
