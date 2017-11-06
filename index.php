<?php

	//turn on debugging message
	ini_set('display errors','on');
	error_reporting(E_ALL);

	//Class to load  classes it finds the file when the program starts to fail for calling a missing class
	class Manage {
		public static function autoload($class){
			include $class . 'php';
		}
	}

	spl_autoload_register(array('Manage','autoload'));


	$obj = new main();
	class main{

		protected $html;

		public function __construct(){

			$hostname = "sql1.njit.edu";
			$username = "ps834";
			$pwd = "q1ZT9FnRO";
			$this->html = htmlLayout::startHTML();

		try{				
				$conn = new PDO("mysql:host=$hostname;dbname=ps834",$username,$pwd);
				printStrings::printText("<b>Connected successfully</b> <br>");
				$condition = "where id < 6";
				$query="select * from accounts $condition";
				$this::executeProgram($conn,$query);

			}catch(PDOException $e){

				printStrings::printText("Error while connecting to the database : " . $e->getMessage());
			}
		}


		public function __destruct(){

			$this->html .=  htmlLayout::endHTML();
			die($this->html);
		}

		
	}




	class runQueries{


		static function runQuery($query,$conn) {


		    try {
					$q = $conn->prepare($query);
					$q->execute();
					$results = $q->fetchAll(PDO::FETCH_ASSOC);
					$q->closeCursor(); 
					return $results;	

			} catch (PDOException $e) {
				http_error("500 Internal Server Error\n\n"."There was a SQL error:\n\n" . $e->getMessage());
			}	  
		}


		 public function selectQuery($conn,$condition){

			$sqlQuery = "select * from accounts $condition";
			$results = runQueries::runQuery($sqlQuery,$conn);
			processResults::countArray($results);
			$tableObj = new processResults();
			$tableObj->createTable($results);

		}


	}


	class processResults{


		public function createTable($results){


			$createData = '<tr>';
			foreach($results as $rows){
				foreach($rows as $values){
					$createData .= '<td>' . $values . '</td>';
				}
 
				$createData .= '</tr>';

			} 

			$this->html .= $createData;

		}

		static function countArray($results){

			print("The No. of Rows returned is " . sizeof($results) . "<br>");

		}


	}


	//Class to create HTML tags
	class htmlLayout{


		//To Start the HTML 
		static function startHTML(){

			return '<html><body><title>PDO Connection</title>';
		}

		//To start table
		static function startTable(){

			return '<table border="1" align = "center">';   
		}

		//To end Table
		static function endTable(){

			return '</table>';
		}

		//To End the HTML 
		static function endHTML(){

			return '</body></html>';
		}
}	
		








?>