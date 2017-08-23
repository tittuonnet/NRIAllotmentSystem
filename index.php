<?php
/*############################################################################
##############################################################################
1. Encoding - base64_encode(data)
2. Decoding - base64_decode(data)
##############################################################################
############################################################################*/
ini_set('max_execution_time', 300); //300 seconds = 5 minutes - To Execute loops for a longer time. Else may cause error during execution.
include_once('db.php'); //Including Database Credentials
$row = 1; $studentData =array();
if (($handle = fopen("file.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        //echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
			$studentData[$c] = mysql_real_escape_string($data[$c]); //Storing each column of a row as array in Student Data.
        }
		$result		= base64_encode(serialize ($studentData)); //Serializing Data to store as array
		$registernumber = $studentData[26]; //Register Number from student data array, "27" is column number of register number in Excel sheet
		$board = $studentData[27]; //Board name from student data array
		$maths = $studentData[29]; //Mark of maths from student data array
		$physics = $studentData[30]; //Mark of physics from student data array
		$chemistry = $studentData[31]; //mark of chemisty from student data array
		$total =  $maths + $chemistry + $physics; //Total mark Sum of Chemistry, Maths, Physics
		$opt1 = $studentData[44]; //Option 1
		$opt2 = $studentData[45]; //Option 2
		$opt3 = $studentData[46]; //Option 3
		$opt4 = $studentData[47]; //Option 4
		//Checking for errors, whether the entered mark is lessthan or equal to zero, can be changed into less than 10 or so.
		if($maths <= 0 || $chemistry <=0 || $physics <=0)
		{
			$error[0]='The marks of physics / chemistry / maths is 0, please verify it';
		}
		if(empty($opt1)&&empty($opt2)&&empty($opt3)&&empty($opt4))
		{
			if(isset($error))
			{
				$i = count($error);
				$i=$i-1;
			}
			else
				$i=0;
			$error[$i]='The Options are empty';
		}
		if(isset($error)) //Checking whether error is set or not
			$error = base64_encode(serialize ($error)); //Serializing Data to store as array
		else
			$error = 0;
		//Inserting into database
		$sql = mysql_query("INSERT INTO `file` (`registernumber`,`data`,`board`,`maths`,`physics`,`chemistry`,`total`,`error`) VALUES ('$registernumber','$result','$board','$maths','$physics','$chemistry','$total','$error')");
		if($sql)
		 echo "Inserted #".$row."<br/>"; //Print if the data is insrted into database
		else
		 echo mysql_error().'<br/>'; // Print error if the data is not inserted into database
		 
		unset($error); //Unsetting Error Vari
	}
    fclose($handle);
}
?>
