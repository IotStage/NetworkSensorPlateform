<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:57
 */

namespace App\DataBase\Table\DAO;


use App\DataBase\Table\DTO\Courbe;

class CourbeDOA
{
    static $CLASS_NAME = "courbe";

    public function getAllDatas(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME;
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function putData(\PDO $bd, Courbe $data){
        $req = "insert into ".static::$CLASS_NAME.'(capteur, type_courbe) VALUES(:capteur, :date)';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'capteur'=> $data->getCapteur(),
            'date'=> $data->getType()
        ));

        return $val;
    }

    public function editDatas(\PDO $bd, Courbe $data){
        $req = "update from ".static::$CLASS_NAME.' SET capteur=:capteur, type_courbe = :date where id=:id';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'capteur'=> $data->getCapteur(),
            'date'=> $data->getType(),
            'id'=> $data->getId()
        ));

        return $val;
    }

    public function deleteDatas(\PDO $bd, Courbe $data){
        $req = "delete from ".static::$CLASS_NAME.' where id=:id';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'id'=> $data->getId()
        ));

        return $val;
    }

    static function getInstance(){
        if(!isset($_SESSION['INSTANCE_OF_'.static::$CLASS_NAME])){
            $class = __CLASS__;
            $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME] = new $class();
        }
        return $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME];
    }
}