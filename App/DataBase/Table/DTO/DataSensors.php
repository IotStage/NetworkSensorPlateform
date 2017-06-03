<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 22/05/2017
 * Time: 21:46
 */

namespace App\DataBase\Table\DTO;


class DataSensors
{
    //static $CLASS_NAME ="data_sensors";
    private $id;
    private $valeur;
    private $date;
    private $capteur;


    function __construct()
    {
        $this->date = date('Y-m-d H:i:s');
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return DataSensors
     */
    public function setId($id)
    {
        $this->id = $id;
        //return $this;
    }

    /**
     * @return mixed
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param mixed $valeur
     * @return DataSensors
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
        //return $this;
    }

    /**
     * @return mixed
     */
    public function getCapteur()
    {
        return $this->capteur;
    }

    /**
     * @param mixed $capteur
     * @return DataSensors
     */
    public function setCapteur($capteur)
    {
        $this->capteur = $capteur;
        //return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }



}