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
use App\DataBase\Table\DTO\Supervision;

class SupervisionDOA
{
    static $CLASS_NAME = "supervision";

    public function getAllDatas(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME;
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function putData(\PDO $bd, Supervision $data){
        $req = "insert into ".static::$CLASS_NAME.'(message, type, date) VALUES(:capteur, :type,:date)';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'capteur'=> $data->getMessage(),
            'date'=> $data->getDate(),
            'type' => $data->getType()
        ));
        if($data->getType() === Supervision::$TYPE_CMD_SUPERVISON[0])
            $id=SupervisionDOA::getLastCTL($bd)[0]->id;
        else
            $id=SupervisionDOA::getLastSupervision($bd)[0]->id;
        $this->putOnHistorique($bd, $data, $id);

        return $val;
    }


    public function deleteDatas(\PDO $bd, Supervision $data){
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
    public static function getLastSupervision(\PDO $bd, $status=null)
    {
        if($status===null){

            $req = "SELECT * FROM ".static::$CLASS_NAME."  where type=:type  ORDER BY id DESC limit 1";
            $ex = $bd->prepare($req);
            $ex->execute(array(
                'type' => Supervision::$TYPE_CMD_SUPERVISON[1],
            ));

        }
        else{
            $req = "SELECT * FROM ".static::$CLASS_NAME."  where type=:type and status=:status ORDER BY id DESC limit 1";
            $ex = $bd->prepare($req);

            $ex->execute(array(
                'type' => Supervision::$TYPE_CMD_SUPERVISON[1],
                'status' => $status
            ));
        }
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public static function getLastCTL(\PDO $bd)
    {
        $req = "SELECT * FROM ".static::$CLASS_NAME."  where type=:type ORDER BY id DESC limit 1";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'type' => Supervision::$TYPE_CMD_SUPERVISON[0]
        ));
        $return = $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        SupervisionDOA::updateStatus($bd, Commande::$STATUS_CMD[0], $return[0]->id);
        return $return;
    }


    public  function getAllMessageCTL(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME." ORDER BY id DESC where type=:id and status =:status";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'id'=> Supervision::$TYPE_CMD_SUPERVISON[0],
            'status' => Commande::$STATUS_CMD[1]
        ));
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public  function getAllMessageSUP(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME." ORDER BY id DESC where type=:id and status =:status";
        //$ex = $bd->query($req);
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'id'=> Supervision::$TYPE_CMD_SUPERVISON[1],
            'status' => Commande::$STATUS_CMD[1]
        ));
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public static function updateStatus(\PDO $bd, $status, $id){
        $req = "update ".static::$CLASS_NAME.' set status=:date where id=:id';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'id'=> $id,
            'date'=> $status
        ));
        return $val;
    }

    public function putOnHistorique(\PDO $bd, Supervision $supervision, $id){
        $historique = (new Historique())
            ->setType($supervision->getType())
            ->setMessage($supervision->getMessage())
            ->setIdRequest($id);
        return HistoriqueDOA::getInstance()->putData($bd, $historique);
    }

    public function getSpecificSupervision(\PDO $bd, $id){
        $req = "SELECT * date FROM ".static::$CLASS_NAME." where id=:id";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'id'=> $id
        ));
        return $ex->fetchAll(\PDO::FETCH_ASSOC);
    }

}