<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 02/06/2017
 * Time: 07:39
 */

namespace App\DataBase\Table\DTO;


class MaladieCapteurs
{

    private $id;
    private $idMaladie;
    private $capteur;
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
     * @return MaladieCapteurs
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdMaladie()
    {
        return $this->idMaladie;
    }

    /**
     * @param mixed $idMaladie
     * @return MaladieCapteurs
     */
    public function setIdMaladie($idMaladie)
    {
        $this->idMaladie = $idMaladie;
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
     * @return MaladieCapteurs
     */
    public function setCapteur($capteur)
    {
        $this->capteur = $capteur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeuil()
    {
        return $this->seuil;
    }

    /**
     * @param mixed $seuil
     * @return MaladieCapteurs
     */
    public function setSeuil($seuil)
    {
        $this->seuil = $seuil;
        return $this;
    }


}