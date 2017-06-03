<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 12:05
 */

$container = $app->getContainer();


$container['bd'] = function () {
    return \App\DataBase\DataBase::getPDO();
};


$container['view'] = function ($container) {
    $dir = dirname(__DIR__);
    $view = new \Slim\Views\Twig($dir."/App/Vue", [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

//$twig->addExtension(new Twig_Extension_Debug());