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

class CommandeDOA
{
    static $CLASS_NAME = "commande";

    public function getAllDatas(\PDO $bd){
        $req = "SELECT * FROM ".static::$CLASS_NAME;
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function putData(\PDO $bd, Commande $data){
        $req = "insert into ".static::$CLASS_NAME.'(message) VALUES(:capteur)';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'capteur'=> $data->getMessage()

        ));

        $this->putOnHistorique($bd, $data, CommandeDOA::getLastCommande($bd)[0]->id);
        return $val;
    }


    public function deleteDatas(\PDO $bd, Commande $data){
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
    public static function getLastCommande(\PDO $bd)
    {
        $req = "SELECT * FROM ".static::$CLASS_NAME." ORDER BY id DESC limit 1";
        $ex = $bd->query($req);
        return $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public static function updateStatus(\PDO $bd, $status, $id){
        $req = "update ".static::$CLASS_NAME.' set status = :date where id=:id';
        //var_dump($req);
        $ex = $bd->prepare($req);
        $val= $ex->execute(array(
            'id'=> $id,
            'date'=> $status
        ));
        return $val;
    }

    public function putOnHistorique(\PDO $bd, Commande $supervision, $id){
        $historique = (new Historique())
            ->setType(Historique::$TYPE_HISTORIQUE[0])
            ->setMessage($supervision->getMessage())
            ->setIdRequest($id);
        return HistoriqueDOA::getInstance()->putData($bd, $historique);
    }

    public function getSpecificCommande(\PDO $bd, $id){
        $req = "SELECT * date FROM ".static::$CLASS_NAME." where id=:id";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'id'=> $id
        ));
        return $ex->fetchAll(\PDO::FETCH_ASSOC);
    }
}