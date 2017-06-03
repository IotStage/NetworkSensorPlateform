<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 02/06/2017
 * Time: 05:13
 */

namespace App\DataBase\Table\DTO;


class Historique
{
    static $TYPE_HISTORIQUE = array(
        'CMD','SUP', 'CTL'
    );
    private $id;
    private $type;
    private $message;
    private $date;
    private $idRequest;


    /**
     * @return mixed
     */
    public function getIdRequest()
    {
        return $this->idRequest;
    }

    /**
     * @param mixed $idRequest
     * @return Historique
     */
    public function setIdRequest($idRequest)
    {
        $this->idRequest = $idRequest;
        return $this;
    }



    public function __construct()
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
     * @return Historique
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Historique
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Historique
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
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
     * @return Historique
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

}