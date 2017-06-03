<?php
namespace App\DataBase;
class SystemSettings {

	public static function getDbHost(){
		return 	"localhost";
	}
	public static function getDbLogin(){
		return 	SystemSettings::getDbHost() == 'localhost' ? "root" : "c3mdialloesp";
	}
	public static function getDbName(){
		return 	SystemSettings::getDbHost() == 'localhost' ? "wsn" :"c3mdialloDB";
	}
	public static function getDbPassword(){
		return SystemSettings::getDbHost() == 'localhost' ?  "root" : "MdialloE16";
	}

	public static function getDbPort(){
	    return SystemSettings::getDbHost() == 'localhost' ? 8889 : 3306;
    }
}
