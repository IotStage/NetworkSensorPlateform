<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:43
 */

namespace App\DataBase\Table\DTO;


class Courbe
{

    private $id;
    private $capteur;
    private $type;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Courbe
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Courbe
     */
    public function setCapteur($capteur)
    {
        $this->capteur = $capteur;
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
     * @return Courbe
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }



}