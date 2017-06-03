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

}