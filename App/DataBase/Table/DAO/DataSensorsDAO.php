<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 15:31
 */

namespace App\DataBase\Table\DAO;


use App\DataBase\Table\DTO\DataSensors;

class DataSensorsDAO extends DOA
{



    static $CLASS_NAME = "data_sensors";

    public function putData(\PDO $bd, DataSensors $data){
        $req = "insert into ".static::$CLASS_NAME.'(valeur, capteur, date) VALUES(:valeur, :capteur, :date)';
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'capteur'=> $data->getCapteur(),
            'valeur'=> $data->getValeur(),
            'date'=> $data->getDate()
        ));
        return $val;
    }

    public function getAllDatas(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME;
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function getDataFromCapteur(\PDO $bd, $capteur){
        $req = "SELECT * FROM ".static::$CLASS_NAME." where capteur=:capteur";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'capteur'=> $capteur
        ));
        $data=array();
        foreach ( $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $key => $tourne){
            $data[]=$tourne->valeur;
         }
         //var_dump($data);die;
         return $data;
    }

    public function getSpecificDataFromCapteur(\PDO $bd, $capteur){
        $req = "SELECT valeur, date FROM ".static::$CLASS_NAME." where capteur=:capteur";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'capteur'=> $capteur
        ));
        $data=array();
        foreach ( $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $key => $tourne){
            $data[]=array($tourne->date,$tourne->valeur);
        }

        return $data;
    }

    static function getInstance(){
        if(!isset($_SESSION['INSTANCE_OF_'.static::$CLASS_NAME])){
            $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME] = new DataSensorsDAO();
        }
        return $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME];
    }


}