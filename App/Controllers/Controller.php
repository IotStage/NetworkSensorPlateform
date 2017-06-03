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


}
