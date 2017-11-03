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
				
				$obj1 = new runSelect();
				$obj1->selectQuery($conn);

			}catch(PDOException $e){

				echo "Error";
			}

		}


	}




	class runSelect{


		 public function selectQuery($conn){
		 	echo "inside select";		 	
			$sqlQuery = "select * from accounts";
			$results = $this::runQuery($sqlQuery,$conn);
			print_r($results);
		}


		public function runQuery($query,$conn) {

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

	}



?>