<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="http://fonts.googleapis.com/css?family=Poppins:800" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">

    <title>Employees</title>
</head>


<!-- MENU OF TRANSACTIONS --> 
<body>
	<header> 
	   <a id="foodrun" href="http://students.engr.scu.edu/~tharnlas/FoodRun/home.html"><h1>FoodRun Delivery Service</h1></a>
    </header>

	    <!-- Complete the URLs -->
	    <div class="main">
	  	<div class="well well-sm">
	  	<a href='http://students.engr.scu.edu/~tharnlas/FoodRun/empTransactions.php?emp=true'>Show all employees.</a>
	 	</div>
	 	<p id="q1"></p>

	 	<div class="well well-sm">
	    <p>Show all employees who work in a given region.</p>
	    <form method = "post" action = "empTransactions.php">
	    <br>
	    <label>Region:&nbsp </label>
	    <input list="regions" name="region"  pattern="San Jose|Santa Clara|Fremont|Palo Alto|San Francisco">
	   		<datalist id="regions">
	   			<option value="Fremont">
	   			<option value="Palo Alto">
	   			<option value="San Jose">
	   			<option value="Santa Clara">
	   			<option value="San Francisco">
	   		</datalist>
	    <input type="submit" name="findByRegion">
	    </form>
		</div>
		<p id="q5"></p>

	 	<div class="well well-sm">
	  	<a href='http://students.engr.scu.edu/~tharnlas/FoodRun/empTransactions.php?supervisor=true'>Generate a list of supervisors and the drivers they supervise.</a>
	 	</div>
	 	<p id="q2"></p>

	 	<div class="well well-sm">
	   	<a href='http://students.engr.scu.edu/~tharnlas/FoodRun/empTransactions.php?perDay=true'>Generate a list of employees in order of the most to least hours worked per day.</a>
	  	</div>
	  	<p id="q3"></p>

	 	<div class="well well-sm">
	   	<p>Increase the wages of the drivers who work at least a given number of hours per day.</p>
	   	<form method="post" action="empTransactions.php">
		<br>
		<label>Hours: </label>
		<input type="number" name="hrWage" id="hrWage" min="1" max="8.00" step="0.01" >
		<label>&nbsp Increase By: </label>
		<input type="number" name="incr" id="incr" min="0" max="1.00" placeholder="MAX: 1.00" step="0.01">
		<input type="submit" name="incrWage">
		</form>
	    </div>
	  	<p id="q4"></p>

	   	<br><br>
    </div>
 
 	<script>
 		var table1 = "", table2 = "", table3 = "", table4 = "", table5 = "";
 	</script>


<?php
	function prepareInput($inputData){
		$inputData = trim($inputData);
		$inputData = htmlspecialchars($inputData);
		return $inputData;
	}
	
	if(isset($_POST['findByRegion'])) {
		$region = $_POST['region'];
		$region = prepareInput($region);

		findByRegion($region);
	}

	
	function findByRegion($region) {
		//connect to your database. Type in your username, password and the DB path
		$conn = oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}
	  
		$query = oci_parse($conn, "SELECT empId, empName, empRegion 
								   FROM Employees
								   WHERE upper(empRegion) = upper(:region)
						  ");

		oci_bind_by_name($query, ':region', $region);

		$table5 = " 	<div class='container'>
				<table class='table table-striped table-bordered'>
				<tr>
					<th>Employee Id</th>
					<th>Name</th> 
					<th>Region</th>
				</tr>
			";

		// Execute the query
		oci_execute($query);

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table5 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> </tr>";
		}

		$table5 .= "</table> </div>";
		echo "<script>";
		echo "table5 = " . json_encode($table5);
		echo "</script>";
		
		OCILogoff($conn);

	}
?>



<?php
	if (isset($_GET['emp'])) {
	    showEmployees();
	}

	if (isset($_GET['supervisor']))
	{
		findBySupervisor(); 
	}

	if (isset($_GET['perDay'])) {
		orderByHoursPerDay();
	}

?>


<!-- QUERY 1 -->
<?php

	function showEmployees() {
		//connect to your database. Type in your username, password and the DB path
		$conn = oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}
	  
		$query = oci_parse($conn, "SELECT empId, empName, empRegion, empPhone, empHours_worked from Employees ");
		$table1 = "<div class='container'>
				<table class='table table-striped table-bordered'>
				<tr>
					<th>Employee Id</th>
					<th>Name</th> 
					<th>Region</th>
					<th>Phone Number</th>
					<th>Hours Worked</th>
				</tr>
			";

		// Execute the query
		oci_execute($query);

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table1 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> <td> $row[3] </td> <td> $row[4] </td> </tr>";
		}

		$table1 .= "</table> </div>";
		echo "<script>";
		echo "table1 = " . json_encode($table1);
		echo "</script>";
		OCILogoff($conn);
	}
?>


<!-- QUERY 2 --> 
<?php
	function findBySupervisor() {
		//connect to your database. Type in your username, password and the DB path
		$conn = oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}
	  
	  	// find supervisor name, driver names
		$query = oci_parse($conn, "SELECT sName, dName FROM
								   (SELECT empName AS sName, empId as sID from Employees natural join Supervisors)
									natural join 
								   (SELECT empName as dName, empId as dID, supervisorId as sID from Employees natural join Drivers)
								   ORDER BY sName
						  ");

		$table2 = " <div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Supervisor Name</th>
						<th>Driver Name</th> 
					</tr>
				  ";

		// Execute the query
		oci_execute($query);

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table2 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> </tr>";
		}

		$table2 .= "</table> </div>";
		
		echo "<script>";
		echo "table2 = " . json_encode($table2);
		echo "</script>";

		OCILogoff($conn);
	}
?>



<!-- QUERY 3 -->
<?php
	function orderByHoursPerDay() {
		//connect to your database. Type in your username, password and the DB path
		$conn = oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}
	  
		$query = oci_parse($conn, "SELECT empId, empName, empHours_worked
        						   FROM Employees 
        						   ORDER BY empHours_worked desc"
        				  );

		$table3 = " <div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Employee Id</th>
						<th>Name</th> 
						<th>Hours Worked</th>
					</tr>
				  ";

		// Execute the query
		oci_execute($query);

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table3 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> </tr>";
		}

		$table3 .= "</table> </div>";

		echo "<script>";
		echo "table3 = " . json_encode($table3);
		echo "</script>";
		
		OCILogoff($conn);
	}
?>


<!-- QUERY 4 -->
<?php 
	if (isset($_POST['incrWage'])) {
		$hours = $_POST['hrWage'];
		$increaseBy = $_POST['incr'];

		if (!empty($hours) && !empty($increaseBy)) {
	     	$hours = prepareInput($hours);
	     	$increaseBy = prepareInput($increaseBy);
	    	increaseWage($hours, $increaseBy);
	    }
	}

	function increaseWage($hours, $increaseBy) {
		//connect to your database. Type in your username, password and the DB path
		$conn = oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}

		$query1 = oci_parse($conn, "UPDATE Drivers
								    SET wage = wage + :increaseBy
								    WHERE empId in (Select empId from Employees natural join Drivers
						            	where empHours_worked >= TO_NUMBER(:hours,9.99))");
		
		oci_bind_by_name($query1, ':hours', $hours);
	    oci_bind_by_name($query1, ':increaseBy', $increaseBy);
		
		$success = oci_execute($query1);

		
		
		if ($success) {
			$query2 = oci_parse($conn, "SELECT empName, emphours_worked, wage from Employees natural join 							  		  Drivers");	
			oci_execute($query2);			
		}
		
		$table4 =  "<div class='container'>
				 	<table class='table table-striped table-bordered'>
				 	<tr>
					<th>Driver Name</th>
					<th>Hours Worked Daily</th>
					<th>Updated Wage</th>
					</tr>
			   ";

		while (($row = oci_fetch_array($query2, OCI_BOTH)) != false) {
			$table4 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> </tr>";
		}

		$table4 .= "</table> </div>";
		
		echo "<script>";
		echo "table4 = " . json_encode($table4);
		echo "</script>";

		OCILogoff($conn);
	}
?>




<script>
	if (table1 != "") {
		$(document).ready(function() {
			$("#q1").append(table1);
		});
	}
	
	if (table2 != "") {
		$(document).ready(function() {
			$("#q2").append(table2);
		});
	}

	if (table3 != "") {
		$(document).ready(function() {
			$("#q3").append(table3);
		});
	}	

	if (table4 != "") {
		$(document).ready(function() {
			$("#q4").append(table4);
		});
	}

	if (table5 != "") {
		$(document).ready(function() {
			$("#q5").append(table5);
		});
	}
</script>

</body>
</html>

	

	
