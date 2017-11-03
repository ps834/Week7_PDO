<?php

	//turn on debugging message
	ini_set('display errors','on');
	error_reporting(E_ALL);

	$obj = new main();

	class main {


		public function __construct(){

			$hostname = "sql1.njit.edu";
			$username = "ps834";
			$pwd = "q1ZT9FnRO";
			$conn = NULL;
		try{				
				$conn = new PDO("mysql:host=$hostname;dbname=ps834",$username,$pwd);
				echo "Connected successfully ";
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
					$results = $q->fetchAll();
					$q->closeCursor(); 
					return $results;	

			} catch (PDOException $e) {
				http_error("500 Internal Server Error\n\n"."There was a SQL error:\n\n" . $e->getMessage());
			}	  
		}


		 public function selectQuery($conn,$condition){

			$sqlQuery = "select * from accounts $condition";
			$results = runQueries::runQuery($sqlQuery,$conn);
			print_r($results);
			$htmlBuilder = new displayTable();
			$htmlBuilder->createTable($results);

		}


	}


	class displayTable extends htmlBuilder{

		public function createTable($results){

			echo "Inside display";

			$createData .= '<tr>';
			foreach($results as $rows){
				foreach($rows as $values){
					$createData .= '<td>' . $values . '</td>';
				}
 
				$createData .= '</tr>';

			} 

			$this->html .= $createData;

		}


	}


	abstract class htmlBuilder{		
		protected $html;

		public function __construct(){
			echo "SOC";
			$this->html .= '<html><body><table>';
		}

		public function __destruct(){
			echo 'EOD';
			$this->html .= '</table></body></html>';
			print($this->html);
		}

	}




?>