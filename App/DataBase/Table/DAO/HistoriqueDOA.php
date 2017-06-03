<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:57
 */

namespace App\DataBase\Table\DAO;


use App\DataBase\Table\DTO\Commande;
use App\DataBase\Table\DTO\Historique;

class HistoriqueDOA
{
    static $CLASS_NAME = "historique";

    public function getAllDatas(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME;
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function putData(\PDO $bd, Historique $data){
        $req = "insert into ".static::$CLASS_NAME.'(message, type, date, id_request) VALUES(:capteur, :type,:date, :id)';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'capteur'=> $data->getMessage(),
            'date'=> $data->getDate(),
            'type' => $data->getType(),
            'id' => $data->getIdRequest()
        ));

        return $val;
    }


    public function deleteDatas(\PDO $bd, Historique $data){
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

    /**
     * @return string
     */
    public static function getLastHistorique(\PDO $bd)
    {
        $req = "SELECT * FROM ".static::$CLASS_NAME."  where type=:type ORDER BY id DESC limit 1";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'type' => Historique::$TYPE_CMD_SUPERVISON[1],
        ));
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public static function getAllCMD(\PDO $bd)
    {
        $req = "SELECT * FROM ".static::$CLASS_NAME."  where type=:type";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'type' => Historique::$TYPE_HISTORIQUE[0]
        ));
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }


    public  function getAllMessageCTL(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME." ORDER BY id DESC where type=:id and status =:status";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'id'=> Historique::$TYPE_HISTORIQUE[2],
            'status' => Commande::$STATUS_CMD[1]
        ));
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public  function getAllMessageSUP(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME." ORDER BY id DESC where type=:id and status =:status";
        //$ex = $bd->query($req);
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'id'=> Historique::$TYPE_HISTORIQUE[1],
            'status' => Commande::$STATUS_CMD[1]
        ));
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }


}