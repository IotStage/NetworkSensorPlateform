<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 26/05/2017
 * Time: 22:45
 */

namespace App\DataBase\Table\DAO;


abstract class DOA
{

   //static $CLASS_NAME = __CLASS__;


    public abstract function getAllDatas(\PDO $bd);

    //public static abstract function getInstance();

}