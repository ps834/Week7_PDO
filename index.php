
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



		public function __construct(){


				//Set Connection Parameters
				$hostname = "sql1.njit.edu";
				$username = "ps834";
				$pwd = "q1ZT9FnRO";

				//Set the condition as and when needed else set is as empty string 
				$condition = "where id < 6";

				//Set Query
				$query="select * from accounts $condition";


				//Connect to Database
				$conn=PDODefinition::openPdoConnection($hostname,$username,$pwd);

				//Execute the Program
				$progObj = new programExecution();
				$progObj->executeProgram($conn,$query);


		}

	}


	class PDODefinition{



		//Connecting to Database using the parameters passed by the main
		static function openPdoConnection($hostname,$username,$pwd){


			try{
				$conn = new PDO("mysql:host=$hostname;dbname=ps834",$username,$pwd);
				printStrings::printText(htmlLayout::setBold("Connected successfully") . htmlLayout::goToNextLine());

			}catch(PDOException $e){

				//Print Database Connection Error
				printStrings::printText("Error while connecting to the database : " . $e->getMessage());
			}
				return $conn;

		}


		//Close Connection
		static function closeConnection($conn){ 

			$conn.close();
		}

	}



	//This Class contains the Layout of the entire program
	class programExecution{


		protected $html;

		public function __construct(){

			//Open HTML 
			$this->html = htmlLayout::beginHTMLTag();
			$this->html = htmlLayout::beginTable();
		}


		//This function will call functions to create table and count no. of records returned
		public function executeProgram($conn,$query){

				$results = runQueries::runQuery($conn,$query);
				$createData = processResults::readRecords($results);
				$countValue = processResults::countRecords($results);
				printStrings::printText($countValue);
				$this->html .= $createData;		

		}


		public function __destruct(){

			//End Table and close HTML
			$this->html .=  htmlLayout::endTable();
			$this->html .=  htmlLayout::endHTML();

			//Print Program output
			printStrings::printText($this->html);

			//Close Database Connection
			PDODefinition::closeConnection($conn);
		}



	}



	class runQueries{



		//This will execute the query passed and return the resultset
		static function runQuery($conn,$query) {

		    try {
		    	
					$q = $conn->prepare($query);
					$q->execute();
					$results = $q->fetchAll(PDO::FETCH_ASSOC);
					/*$link = mysql_connect('sql1.njit.edu', 'ps834', 'q1ZT9FnRO');
					$res = mysql_query($query, $link);*/
/*					echo mysql_data_seek($result, 0);*/
					$q->closeCursor(); 
					return $results;	

			} catch (PDOException $e) {
				http_error("500 Internal Server Error\n\n"."There was a SQL error:\n\n" . $e->getMessage());
			}	  
		}

	}



	class processResults{

		

		//Function to generate results in tabular format as per the resultset passed
		static function readRecords($results){

			$i=0;
			$createData = htmlLayout::beginTableRow(); 
			foreach($results as $rows){
				foreach($rows as $key => $values){
					if($i==0){
						$createData .= htmlLayout::createTableData(htmlLayout::setBold(htmlLayout::toUpperCase($key)));
					}else{
						$createData .= htmlLayout::createTableData($values);
					}
					
				}
 				$i++;
				$createData .= htmlLayout::endTableRow();

			} 
			return $createData;
		}

		//Function to count the number of data in the result set
		static function countRecords($results){

			$text = "No. of Records: " . sizeof($results) . htmlLayout::goToNextLine();
			return $text;

		}
		
	}




class printStrings{

	//This will print all the String passed to it
	static function printText($text){

			print($text);
	}
}



//Class containing all HTML tags
class htmlLayout{


	//Start HTML
	static function beginHTMLTag(){

		return '<html><body><title>PDO Connection</title>';
	}


	//Start Table
	static function beginTable(){

		return '<table border="1" align = "center">';
	}


	//Open Table Row
	static function beginTableRow(){

		return '<tr>';
	}

	//Close Table Row
	static function endTableRow(){

		return '</tr>';
	}



	//End Table
	static function endTable(){

		return '</table>';

	}


	static function createTableData($values){

		return '<td>' . $values . '</td>';
	}

	//End HTML
	static function endHTML(){

		return '</body></html>';
	}

	//Set Text Bold
	static function setBold($values){

		return '<b>' . $values . '</b>';
	}

	//Prints data in the next line
	static function goToNextLine(){

		return '<br>';
	}

	//Converts the entire String to upper case
	static function toUpperCase($value){

		return strtoupper($value);
	}

}

?>