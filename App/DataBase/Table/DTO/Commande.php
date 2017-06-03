<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:43
 */

namespace App\DataBase\Table\DTO;


class Commande
{

    static $STATUS_CMD = array(
        "LU","NONLU"
    );
    private $id;
    private $message;
    private $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Commande
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Commande
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Commande
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }




}