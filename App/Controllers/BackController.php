<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 12:03
 */

namespace App\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use App\DataBase\Table\DTO\Utilisateur;
use App\DataBase\Table\DAO\UtilisateurDOA;
session_start();

class BackController extends Controller
{
	public function login(Request $request, Response $response){

	    return $this->render($response, 'Pages/login.html.twig');
    }

	public function suscribe(Request $request, Response $response){
		$param = $request->getParams();
		if (count($param)>0) {
			//echo "merci pour l'inscription";
			$user = (new Utilisateur())
				->setNom($param["nom"])
				->setPrenom($param["prenom"])
				->setMail($param["mail"])
				->setTelephone($param["telephone"])
				->setPassword($param["password"]);
			UtilisateurDOA::getInstance()->putData($this->bd, $user);
		} 
      return $this->render($response, 'Pages/suscribe.html.twig');
    }


}