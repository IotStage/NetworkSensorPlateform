<?php

namespace App\DataBase;

use App\DataBase\DatabaseInfo;
use \PDO;

class DataBase {
	
	private static $pdo;
	
	public static function getPDO(){
		if(!isset(static ::$pdo)){
			static::$pdo  = static::getDatabaseInfo()->getPDO();
		}
		return static::$pdo;
		
	}
	
	private static function getDatabaseInfo(){
		return new DatabaseInfo();
	}

	public function query($statement){
	    $app = static::$pdo -> query($statement);
	    $app->setAttibutes(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    return $app;
    }

    //public function prepare($s)
	
}
