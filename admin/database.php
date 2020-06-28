<?php


	class DataBase {

		private static $dbHost = "localhost";
		private static $dbname = "burgercode";
		private static $dbuser = "root";
		private static $dbuserPassword = "";
		private static $connection = null;
		
		public static function connect() {
			try {
				self::$connection = new PDO("mysql:host=".self::$dbHost.";dbname=".self::$dbname.";charset=utf8", self::$dbuser, self::$dbuserPassword);
			} 
			catch(PDOException $e) {
				die($e->getMessage());
			}
			return self::$connection;
		}

		public static function disconnect() {
			self::$connection = null;
		}

	}

	DataBase::connect();

?>