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

	class main {


		public function __construct(){

			$hostname = "sql1.njit.edu";
			$username = "ps834";
			$pwd = "q1ZT9FnRO";
			$conn = NULL;
		try{				
				$conn = new PDO("mysql:host=$hostname;dbname=ps834",$username,$pwd);
				echo "Connected successfully <br>";
				$condition = "where id < 6";
				$obj1 = new runQueries();
				$obj1->selectQuery($conn,$condition);

			}catch(PDOException $e){

				echo "Error";
			}
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
			$tbLObj = new processResults();
			//$tbLObj->createTable($results);
			processResults::countArray($results);
		}


	}


	abstract class buildHTML{

		protected $html;

		//Open HTML 
		public function __construct(){

			echo "SOC";

			//$this->html .= '<html><body><table>';
		}

		//Closing HTML 
		public function __destruct(){
        	

        	echo "destruct";
        	//$this->html .= '</table></body></html>';

        	//printing the results appended to HTML 
        	//print($this->html);

    	}


	}



	class processResults extends buildHTML{


		protected $html;
		public function __construct(){
			$this->html .= '<html><body><table border="1" align = "center">';
		}

		public function createTable($results){


			$createData = '<tr>';
			foreach($results as $rows){
				foreach($rows as $values){
					$createData .= '<td>' . $values . '</td>';
				}
 
				$createData .= '</tr>';

			} 

			$this->html.= $createData;

		}

		static function countArray($results){

			print("The No. of Rows returned is " . sizeof($results));

		}
		
		

		public function __destruct(){
			$this->html .= '</table></body></html>';
			print_r($this->html);
		}



	}






?>