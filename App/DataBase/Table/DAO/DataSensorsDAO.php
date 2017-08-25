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
        $this->updateParam($bd, $data->getCapteur());
        $this->updateNormal($bd, $data->getCapteur(), $data->getValeur());


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

    public function getDataFromManyCapteur(\PDO $bd, $capteurs){
        $nbsensor= count($capteurs);
        $req = "SELECT valeur, date, capteur FROM ".static::$CLASS_NAME." where ";
        $requete="";
        $i=0;
        if($nbsensor>0){
            foreach ($capteurs as $key => $value) {
                $requete.=" capteur=:".$key;
                if($i!=$nbsensor-1)
                    $requete.=" or ";
                $i++;
            }

        $req.=$requete;
        //$req.=" order by id";
        $ex = $bd->prepare($req);
        $ex->execute($capteurs); 
        return $ex->fetchAll(\PDO::FETCH_ASSOC);

        
        

        }
    }

    public function getSpecificDataFromCapteur(\PDO $bd, $capteur){
        $req = "SELECT valeur, date FROM ".static::$CLASS_NAME." where capteur=:capteur";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'capteur'=> $capteur
        ));
        $data=array();
        foreach ( $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $key => $tourne){
            $data[]=array($tourne->date,$tourne->valeur+0.0);
        }
        return $data;
    }

    public function getLastDataSpecificCapteur(\PDO $bd, $capteur){
        $req = "SELECT valeur, date FROM ".static::$CLASS_NAME."  WHERE capteur =:capteur and date BETWEEN :date_deb AND :date_fin ORDER BY date";
        $reponse = $bd->prepare($req);
        $datte = date("Y-m-d");
        //var_dump($datte);
        $reponse->execute(
            array(
                "date_deb" => "$datte 00:00:00", //"$datte 11:0:0",
                "date_fin" => "$datte 23:59:59",  //"$datte 16:59:0"
                "capteur"  => $capteur
            )
        );

        $donnees=$reponse->fetchAll(\PDO::FETCH_ASSOC);

        //var_dump($donnees);
        $res = array();
        foreach ($donnees as $key => $value) {
            $date_heure = explode(" ",$value['date']);
            $res[$key] = array(
                "data" => $value['valeur'],
                "datte" => $date_heure[0],
                "heure" => $date_heure[1]
            );
        }

        return $res;
    }

    static function getInstance(){
        if(!isset($_SESSION['INSTANCE_OF_'.static::$CLASS_NAME])){
            $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME] = new DataSensorsDAO();
        }
        return $_SESSION['INSTANCE_OF_'.static::$CLASS_NAME];
    }

    static function getDataFromCapteurWithSpecificID(\PDO $bd, $capteur, $id){
        $req = "SELECT * FROM ".static::$CLASS_NAME." where id>:id and capteur=:capteur ";
        $ex = $bd->prepare($req);
        $ex->execute(array(
            'capteur'=> $capteur,
            'id'=> $id
        ));
        $data=array();
        $last_id = 0;
        foreach ( $ex->fetchAll(\PDO::FETCH_CLASS, __CLASS__) as $key => $tourne){
            $data[]=$tourne->valeur;
            $last_id = $tourne->id;
        }
        //var_dump($data);die;
        return array(
            'datas' => $data,
            'last_id' => $last_id
        );
    }

    private function ecartType($moy, $moyCarre){
        return sqrt($this->variance($moy, $moyCarre));
    }

    private function variance($moyenne, $moyenne_carre){
        return $moyenne_carre - $moyenne;
    }

    private function lastMoyenne($moyenne, $last_id, $valeur){
        $n = $last_id/10;
        $nb=count($valeur);
        var_dump($nb);
        var_dump(($n+$nb));
        return ($n*$moyenne + array_sum($valeur))/($n+$nb);
    }

    private function lastMoyenneCarre($moyenne, $last_id, $valeur){
        $n = $last_id/10;
        $nb=count($valeur);
        var_dump($nb);
        var_dump(($n+$nb));
        $som=0;
        foreach ($valeur as $key => $value){
            $som+=($value*$value);
        }
        return ($n*$moyenne + $som)/($n+$nb);
    }

    private function updateParam(\PDO $bd,$capteur){
        $table = CapteurDOA::getMoyenneFromSpecificCapteur($bd, $capteur)[0];
        $moyenne = $table["moyenne"];
        $moyenneCarre = $table["moyenne_carre"];

        //if($table["moyenne"]!=0){
        $array = DataSensorsDAO::getDataFromCapteurWithSpecificID($bd, $capteur, $table["last_id"]);
        $data =  $array["datas"];
        $last_id = $array["last_id"];
        if($last_id != 0) {//$last_id = $table["last_id"];
            $lastmoyenne = $this->lastMoyenne($moyenne, $last_id, $data);
            $lastmoyenneCarre = $this->lastMoyenneCarre($moyenneCarre, $last_id, $data);
            CapteurDOA::updateMoyenne($bd, $capteur, $lastmoyenne);
            CapteurDOA::updateMoyenneCarre($bd, $capteur, $lastmoyenneCarre);
            CapteurDOA::updateLastId($bd, $capteur, $last_id);
        }

    }

    private function updateNormal( \PDO $bd,$capteur, $valeur){
        $table = CapteurDOA::getMoyenneFromSpecificCapteur($bd, $capteur)[0];
        $moyenne = $table["moyenne"];
        $moyenneCarre = $table["moyenne_carre"];
        $ecartType = $this->ecartType($moyenne, $moyenneCarre);
        $normal = ($valeur - $moyenne)/$ecartType;
        CapteurDOA::updateNormal($bd, $capteur, $normal);
    }


}