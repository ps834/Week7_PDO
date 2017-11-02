<?php


	$obj = new main();


	class main {


		public function __construct(){

			$hostname = "sql1.njit.edu";
			$username = "ps834";
			$pwd = "q1ZT9FnRO";
			$connection = NULL;
		try{

				$connection = new PDO("mysql:host=$hostname;dbname=ps834",$username,$pwd);
				echo "Connected successfully ";
				$obj1 = new displayResults();

			}catch(PDOException $e){

				echo "Error";
			}

		}


	}


	class fetchResult{


		 public function selectQuery(){


			$lessThanID = 6;
			$sqlQuery = "select * from accounts where id < $lessThanID";
			$results = runQuery($sqlQuery);
			if(count($results)>0){

				echo "results is here";
				foreach ($$results as $value) {
					echo $value["id"];
				}
			}

		}

	}


	class displayResults{


		public function __construct(){
			echo "Hie";
			$fetchObj = new fetchResult();
			$fetchObj->selectQuery;

		}



	}



?>