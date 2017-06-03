<?php
namespace App\DataBase;
use App\DataBase\SystemSettings;
use \PDO;
class DatabaseInfo {
	
	private $host;
	private $name;
	private $login;
	private $password;
	private $port;
	private $pdo;

	public function __construct()

    {
        $this->host = SystemSettings::getDbHost();
        $this->name = SystemSettings::getDbName();
        $this->login = SystemSettings::getDbLogin();
        $this->password= SystemSettings::getDbPassword();
        $this->port = SystemSettings::getDbPort();

    }

    public function getPDO()
	{

	    if($this->pdo === null){
            $req = "mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->name;
            $bdd = new PDO($req,$this->login, $this->password);
            $this->pdo = $bdd;
        }


	    return $this->pdo;
	}

	public function getHost()
	{
	    return $this->host;
	}

	public function setHost($host)
	{
	    $this->host = $host;
	}

	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function getLogin()
	{
	    return $this->login;
	}

	public function setLogin($login)
	{
	    $this->login = $login;
	}

	public function getPassword()
	{
	    return $this->password;
	}

	public function setPassword($password)
	{
	    $this->password = $password;
	}
}
