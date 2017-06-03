<?php
/**
 * Created by PhpStorm.
 * User: bng
 * Date: 23/05/2017
 * Time: 17:28
 */

namespace App;


class Constantes
{

    static $DEFAULT_URL = "http://localhost:8888/slim";
    static $TYPE_COURBE_LINE = "line";
    static $TYPE_COURBE_AREA = "area";
    static $TYPE_COURBE_COLUMN = "column";
    static $TYPE_COURBE_BARE = "bare";
    static $TYPE_COURBE_PIE = "pie";
    static $TYPE_COURBE_GAUGE = "gauge";


    static function getallTypeCourbe(){
        return array(
            static::$TYPE_COURBE_LINE,
            static::$TYPE_COURBE_AREA,
            static::$TYPE_COURBE_COLUMN,
            static::$TYPE_COURBE_BARE,
            static::$TYPE_COURBE_PIE,
            static::$TYPE_COURBE_GAUGE
        );
    }

}