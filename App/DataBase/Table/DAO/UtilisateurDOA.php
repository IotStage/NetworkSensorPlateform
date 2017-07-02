<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 30/06/2017
 * Time: 00:20
 */

namespace App\DataBase\Table\DAO;


use App\DataBase\Table\DTO\Utilisateur;

class UtilisateurDOA extends DOA
{
    static $CLASS_NAME = "utilisateur";

    public function getAllDatas(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME;
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    static function getInstance(){
        if(!isset($_SESSION['INSTANCE_OF_'.static::$CLASS_NAME])){
            $class = __CLASS__;
            $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME] = new $class();
        }
        return $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME];
    }

    public function putData(\PDO $bd, Utilisateur $data){
        $req = "insert into ".static::$CLASS_NAME.'(nom, prenom, mail, telephone, profil, password) VALUES(:nom, :prenom, :mail,:telephone, :profil, :pass)';
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'nom'=> $data->getNom(),
            'prenom'=> $data->getPrenom(),
            'mail'=> $data->getMail(),
            'telephone' => $data->getTelephone(),
            'profil' => $data->getProfil(),
            'pass' => $data->getPassword()

        ));
        return $val;
    }

}