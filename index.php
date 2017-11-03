<?php
//ini_set('display_errors')

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




	class runQueries
	{


		static function runQuery($query,$conn) {


		    try {
					$q = $conn->prepare($query);
					$q->execute();
					$results = $q->fetchAll();
					echo "fetch!!";
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
			$display = new displayResults();
			$display->displayTable();

		}


	}


	class displayResults{

		function displayTable($results){

			$this->html .= '<tr>';
			foreach($results as $rows){
				foreach($rows as $values){

				}
			} 



		}


	}


	abtract class html{

		protected $html;

		public function __construct(){

			$this->html = '<html><body><table>';
		}

		public function __destruct(){

			$this->html .= '</table></body></html>';
		}

	}




?>