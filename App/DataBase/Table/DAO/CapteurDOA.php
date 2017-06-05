<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:18
 */

namespace App\DataBase\Table\DAO;


class CapteurDOA extends DOA
{
    static $CLASS_NAME = "capteur";

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

    static function getSpecificCapteur(\PDO $bd, $id){
        $req = "SELECT * FROM ".static::$CLASS_NAME." where id=:id";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'id'=>$id
        ));
        return $ex->fetch(\PDO::FETCH_CLASS, __CLASS__);
    }

    static function getMoyenneFromSpecificCapteur(\PDO $bd, $capteur){
        $req = "SELECT moyenne,last_id, moyenne_carre, normale FROM ".static::$CLASS_NAME." where nom=:nom";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'nom'=>$capteur
        ));
        return $ex->fetchAll(\PDO::FETCH_ASSOC);
    }

    static function updateMoyenne(\PDO $bd, $capteur, $moyenne){
        $req = "update ".static::$CLASS_NAME." set moyenne=:moyenne where nom=:nom";
        $ex = $bd->prepare($req);
        return $ex->execute(array(
            'nom'=>$capteur,
            'moyenne'=>$moyenne
        ));
        //return $ex->fetchAll(\PDO::FETCH_ASSOC);
    }

    static function updateMoyenneCarre(\PDO $bd, $capteur, $moyenne){
        $req = "update ".static::$CLASS_NAME." set moyenne_carre=:moyenne where nom=:nom";
        $ex = $bd->prepare($req);
        return $ex->execute(array(
            'nom'=>$capteur,
            'moyenne'=>$moyenne
        ));
        //return $ex->fetchAll(\PDO::FETCH_ASSOC);
    }

    static function updateLastId(\PDO $bd, $capteur, $id){
        $req = "update ".static::$CLASS_NAME." set last_id=:id where nom=:nom";
        $ex = $bd->prepare($req);
        return $ex->execute(array(
            'nom'=>$capteur,
            'id'=>$id
        ));
        //return $ex->fetchAll(\PDO::FETCH_ASSOC);
    }

    static function updateNormal(\PDO $bd, $capteur, $normal){
            $req = "update ".static::$CLASS_NAME." set normale=:normal where nom=:nom";
            $ex = $bd->prepare($req);
            return $ex->execute(array(
                'nom'=>$capteur,
                'normal'=>$normal
            ));
            //return $ex->fetchAll(\PDO::FETCH_ASSOC);
        }

}