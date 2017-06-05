<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:18
 */

namespace App\DataBase\Table\DAO;


use App\DataBase\Table\DTO\MaladieCapteurs;

class MaladieCapteursDOA extends DOA
{
    static $CLASS_NAME = "maladie_capteur";

    public function getAllDatas(\PDO $bd){
        $req = "select maladie_capteur.id, capteur, seuil, nom, id_maladie from maladie_capteur, maladie where maladie.id=maladie_capteur.id_maladie ORDER BY maladie.nom";
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function editDatas(\PDO $bd, MaladieCapteurs $data){
        $req = "update from ".static::$CLASS_NAME.' SET id_maladie=:maladie, capteur =:date, seuil=:seuil where id=:id';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'maladie'=> $data->getIdMaladie(),
            'date'=> $data->getCapteur(),
            'seuil'=> $data->getSeuil(),
            'id'=> $data->getId()
        ));
        return $val;
    }

    public function putDatas(\PDO $bd, MaladieCapteurs $data){
        $req = "insert into ".static::$CLASS_NAME.'(id_maladie, capteur, seuil) VALUES(:id_maladie, :capteur, :seuil)';
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'id_maladie'=> $data->getIdMaladie(),
            'capteur'=> $data->getCapteur(),
            'seuil'=> $data->getSeuil()
        ));
        return $val;
    }

    public function deleteDatas(\PDO $bd, MaladieCapteurs $data){
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

    static function getAllDataSensorFromSpecificMaladie(\PDO $bd, $id){
        $req = "SELECT * from ".static::$CLASS_NAME." where id_maladie = :id";
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'id'=> $id
        ));

        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    static function getSeuilFromSpecificSensor(\PDO $bd, $capteur, $nom){
        $id = MaladieDOA::getMaladieByName($bd,$nom)[0]['id'];
        $req = "SELECT seuil from ".static::$CLASS_NAME." where id_maladie = :id and capteur=:capteur";
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'id'=> $id,
            'capteur'=> $capteur
        ));

        return $ex->fetchAll(\PDO::FETCH_ASSOC);
    }


}