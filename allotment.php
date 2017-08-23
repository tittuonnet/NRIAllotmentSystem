<?php
/*############################################################################
##############################################################################
For Serializing we are using "base64" encoding, in order to secure data
1. Encoding - base64_encode(data)
2. Decoding - base64_decode(data)
##############################################################################
##############################################################################
allotment.php - For Preparing Allotment List.
Working - Fetching Data From Rank List and priting data.
##############################################################################
############################################################################*/

ini_set('max_execution_time', 300); //300 seconds = 5 minutes - To Execute loops for a longer time. Else may cause error during execution.
include_once('db.php'); //Including Database Credentials
if(isset($_GET['dept']))
{
	$dept = $_GET['dept'];
	//SQL Query
	
	$result = mysql_query("SELECT * FROM `ranklist` WHERE dept='" . mysql_real_escape_string($dept) . "'") or die(mysql_error());
	
	while($row = mysql_fetch_array($result))
	{
		$rank = $row['rank'];
		
		if($rank<=20)
			echo "CF - Rank - $rank<br/>";
		else	
			echo "WL - Rank - ". ($rank - 20) ."<br/>";
				
		echo "Register Number -".$row['registernumber']."<br/>";
		echo "Name - ".$row['name']."<br/>";
				
			echo "<br/><br/>";
	}
}
else
{
	echo "Please enter URL like allotment.php?dept=DEPT_CODE";
}
?>