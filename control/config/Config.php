<?php


class Config {

	private $dbHost	=	'localhost';
	private $dbName	=	'controlmas';
	private $dbPass	=	'9C9UAaqKecHRZA79';
	private $dbUser	=	'LNWW(345';

	public function Connection(){
		$jdbc	=	"mysql:host=$this->dbHost; dbname=$this->dbName";
		$conn 	=	new PDO($jdbc , $this->dbUser , $this->dbPass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$conn->exec("set names utf8");
		return $conn;
	}
}









?>