<?php
/*############################################################################
##############################################################################
For Serializing we are using "base64" encoding, in order to secure data
1. Encoding - base64_encode(data)
2. Decoding - base64_decode(data)
##############################################################################
##############################################################################
rank-list.php - For Preparing Rankilst.
Working - Fetching Data From Student Details and sorting and filtering according to our conditions and storing data into ranklist table
##############################################################################
##############################################################################
Fetching All Data From table name "file"
ORDER BY
1. Total Mark - Descending
2. Mark of Maths - Descending
3. Mark of Physics - Descending
4. Mark of Chemistry - Descending
5. Where no Errors, For Preparing ranklist we wont consider Students with invalid data.
##############################################################################
############################################################################*/

ini_set('max_execution_time', 300); //300 seconds = 5 minutes - To Execute loops for a longer time. Else may cause error during execution.
include_once('db.php'); //Including Database Credentials
//SQL Instruction For Fetching Data
$result = mysql_query("SELECT * FROM `file` WHERE error='0' ORDER BY total DESC,maths DESC,physics DESC,chemistry DESC");
//Running WHILE data / row which satisfy above conditions
$options = array(); $studentdata = array(); $i=0;

//stores the total alloted seats CF/WF for each batch
$allotment_count = array();
$allotment_count['CS'] = 0;
$allotment_count['EC'] = 0;
$allotment_count['EE'] = 0;
$allotment_count['EI'] = 0;

while($row = mysql_fetch_array($result))
{
	$studentdata = $row['data']; //Getting Student Data From Database
	$studentdata = unserialize(base64_decode ($studentdata)); //Decoding Serialized Data
	$registernumber = $row['registernumber']; //Student Register Number
	$total = $row['total']; //Total Mark of Student
	$name = $studentdata[2]; //Student Name
	$options[0] = $studentdata[44]; //Storing value of First Option in Options Array
	$options[1] = $studentdata[45]; //Storing value of Second Option in Options Array
	$options[2] = $studentdata[46]; //Storing value of Third Option in Options Array
	$options[3] = $studentdata[47]; //Storing value of Forth Option in Options Array
	 
	
	//check each options of the current student
	for($optnum = 0; $optnum<=3; ++$optnum) {
		if(empty($options[$optnum])) break; //no more options
		
		if($allotment_count[$options[$optnum]] < 120) {
			$allotment_count[$options[$optnum]]++; //one more seat alloted for the department
			$sql = mysql_query("INSERT into `ranklist` (`registernumber`,`name`,`dept`,`rank`) values ('$registernumber','$name','{$options[$optnum]}','{$allotment_count[$options[$optnum]]}')");
			if($sql)
			 echo "Added Register Number - ".$registernumber." into Ranklist<br/>";
			else
			 echo $registernumber." - ".mysql_error()."<br/>";
			
			//if CF, stop, else check for lower options
			if($allotment_count[$options[$optnum]] <= 20)
				break;
			else
				continue;
		}
	}
}
?>