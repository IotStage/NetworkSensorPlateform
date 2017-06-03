<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 22/05/2017
 * Time: 21:43
 */

namespace App\DataBase\Table\DTO;


class Maladie
{

    private $id;
    private $nom;
    private $description;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Maladie
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     * @return Maladie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Maladie
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}