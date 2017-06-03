<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:18
 */

namespace App\DataBase\Table\DAO;


use App\DataBase\Table\DTO\Maladie;

class MaladieDOA extends DOA
{
    static $CLASS_NAME = "maladie";

    public function getAllDatas(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME;
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function putDatas(\PDO $bd, Maladie $data){
        $req = "insert into ".static::$CLASS_NAME.' (nom, description) values (:maladie, :date)';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'maladie'=> $data->getNom(),
            'date'=> $data->getDescription(),
        ));

        return $val;
    }
    public function editDatas(\PDO $bd, Maladie $data){
        $req = "update from ".static::$CLASS_NAME.' SET nom=:maladie, description = :date where id=:id';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'maladie'=> $data->getNom(),
            'date'=> $data->getDescription(),
            'id'=> $data->getId()
        ));

        return $val;
    }


    public function deleteDatas(\PDO $bd, Maladie $data){
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


    static function getSpecificMaladie(\PDO $bd, $id){
        $req = "SELECT * FROM ".static::$CLASS_NAME." where id=:id";
        $ex = $bd->prepare($req);
        $ex->execute(array(
           'id'=>$id
        ));
        return $ex->fetch(\PDO::FETCH_CLASS, __CLASS__);
    }
}