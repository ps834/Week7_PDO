
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


		//Opening PDO Connection and calling Execute Program
		public function __construct(){

			//Set Connection Parameters
			$hostname = "sql1.njit.edu";
			$username = "ps834";
			$pwd = "q1ZT9FnRO";

			//Create Table
			$this->html = htmlLayout::startHTML();
			$this->html = htmlLayout::startTable();

		try{		

				//Connection to Database		
				$conn = new PDO("mysql:host=$hostname;dbname=ps834",$username,$pwd);
				printStrings::printText("<b>Connected successfully</b> <br>");


				//Set the condition as and when needed else set is as empty string 
				$condition = "where id < 6";

				//Set Query
				$query="select * from accounts $condition";

				//Executing the Program
				$this::executeProgram($conn,$query);

			}catch(PDOException $e){

				//Print Database Connection Error
				printStrings::printText("Error while connecting to the database : " . $e->getMessage());
			}
		}


		//Funtion to call different methods
		public function executeProgram($conn,$query){



				$results = runQueries::runQuery($conn,$query);
				$createData = processResults::createTable($results);
				$countValue = processResults::countArray($results);
				printStrings::printText($countValue);
				$this->html .= $createData;
				

		}


		//Close Connection
		static function closeConnection($conn){

			$conn.close();
		}


		public function __destruct(){

			//End Table
			$this->html .=  htmlLayout::endTable();
			$this->html .=  htmlLayout::endHTML();

			//Print Program output
			printStrings::printText($this->html);

			$this::closeConnection($conn);
		}

		
	}




	class runQueries{



		//This will execute the query passed and return the resultset
		static function runQuery($conn,$query) {


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

	}



	class processResults{

		

		//Function to Create Table Data as per the result set passed
		static function createTable($results){


			$createData = '<tr>'; 
			foreach($results as $rows){
				foreach($rows as $values){
					$createData .= '<td>' . $values . '</td>';
				}
 
				$createData .= '</tr>';

			} 
			return $createData;
		}

		//Function to count the number of data in the result set
		static function countArray($results){

			$text = "The No. of Rows returned is " . sizeof($results) . "<br>";
			return $text;

		}
		
	}




class printStrings{

	//This will print all the String passed to it
	static function printText($text){

			print($text);
	}
}


class htmlLayout{


	//Start HTML
	static function startHTML(){

		return '<html><body><title>PDO Connection</title>';
	}


	//Start Table
	static function startTable(){

		return '<table border="1" align = "center">';
	}


	//End Table
	static function endTable(){

		return '</table>';

	}

	//End HTML
	static function endHTML(){

		return '</body></html>';
	}

}

?>