<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 22/05/2017
 * Time: 21:43
 */

namespace App\DataBase\Table;


class Capteur
{

    private $id;
    private $nom;
    private $description;
    private $seuil;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Capteur
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
     * @return Capteur
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
     * @return Capteur
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}