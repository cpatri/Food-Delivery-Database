<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Poppins:800" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">

      <title>Employees</title>
</head>

<body>
	<header> 
	   <a id="foodrun" href="http://students.engr.scu.edu/~tharnlas/FoodRun/home.html"><h1>FoodRun Delivery Service</h1></a>
    </header>

	    <!-- Complete the URLs -->
	    <div class="main">

	  	<div class="well well-sm">
	  	<a href='http://students.engr.scu.edu/~tharnlas/FoodRun/restaurantTransactions.php?rest=true'>Show all restaurants.</a>
	 	</div>
	 	<p id="q15"></p>

	 	<div class="well well-sm">
	   	<p>Show all restaurants located in a specific region.</p>
	   	<form method="post" action="restaurantTransactions.php">
	   		<label>Region: &nbsp</label>
	   		<input list="regions" name="region"  pattern="San Jose|Santa Clara|Fremont|Palo Alto|San Francisco">
	   		<datalist id="regions">
	   			<option value="Fremont">
	   			<option value="Palo Alto">
	   			<option value="San Jose">
	   			<option value="Santa Clara">
	   			<option value="San Francisco">
	   		</datalist>
	   		<input type="submit" name="byRegion">
	   	</form> 
	  	</div>
	  	<p id="q12">

	  	<div class="well well-sm">
	  	<p>Generate a list of menu items and their prices from a given restaurant.</p>
	  	<br>
	  	<form method="post" action="restaurantTransactions.php">
	  		<label>Restaurant ID: </label>
	  		<input type="text" name="rID" id="rID">
	  		<input type="submit" name="getMenu">
	  	</form>
	 	</div>
	 	<p id="q13">

	  	<div class="well well-sm">
	  	<a href='http://students.engr.scu.edu/~tharnlas/FoodRun/restaurantTransactions.php?mostOrders=true'>Show the restaurant that received the most number of orders this month.</a>
	 	</div>
	 	<p id="q14">

		</div>
	   	<br><br>
    </div>

    <script>
    	var table12 = "", table13 = "", table14 = "", table15 = "";
    </script>
     

<!-- QUERIES AND TRANSACTIONS -->
<?php
	if (isset($_GET['rest'])) {
	    showRestaurants();
	}

	if (isset($_GET['mostOrders'])) {
		mostOrders();
	}
?>


<?php

	function mostOrders() {
		//connect to your database. Type in your username, password and the DB path
		$conn = oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}
	  
		$query = oci_parse($conn, "SELECT rId, rName, count(orderID) no_of_orders
								   FROM Orders natural join Restaurants
								   WHERE EXTRACT(MONTH FROM time_made) = EXTRACT(MONTH from SYSDATE)
								   GROUP BY rID, rName
								   HAVING count(orderID) = (SELECT MAX(count(orderID))
								   						   FROM Orders
								   						   WHERE EXTRACT(MONTH FROM time_made) = EXTRACT(MONTH from SYSDATE)
								   						   GROUP BY rID)"
						  );

		$table14 = " <div class='container'>
					<table class='table table-striped table-bordered'>					
					<tr>
						<th>Restaurant ID</th>
						<th>Name</th> 
						<th>Number of Orders</th>
					</tr>
				  ";

		// Execute the query
		oci_execute($query);

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table14 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td></tr> ";
		}

		$table14 .= "</table> </div>";

		echo "<script>";
		echo "table14 = " . json_encode($table14); 
		echo "</script>";

		OCILogoff($conn);
	}

?>


<!-- FORMS NECESSARY FOR QUERIES -->
<?php
	if(isset($_POST['getMenu'])) {

		$rID = $_POST['rID'];

		if (!empty($rID)){
	     	$rID = prepareInput($rID);
	     	$rID = str_pad($rID, 5);
	    	getMenu($rID);
	    }
	}

		function prepareInput($inputData){
			$inputData = trim($inputData);
	  		$inputData = htmlspecialchars($inputData);
	  		return $inputData;
	}

	function getMenu($rID)
	{
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}

		$query = oci_parse($conn, "SELECT itemName, itemPrice
        						   FROM MenuItems 
       							   WHERE upper(rId) = upper(:rID)");

		oci_bind_by_name($query, ':rID', $rID);

		// Execute the query
		oci_execute($query);

		$table13 = " <div class='container'>
					 <table class='table table-striped table-bordered'>
					 <tr>
						<th>Menu Item</th> 
						<th>Price</th>
					 </tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table13 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> </tr>";
		}
		
		$table13 .= "</table> </div>";

		echo "<script>";
		echo "table13 = " . json_encode($table13); 
		echo "</script>";

		OCILogoff($conn);
	}

?>


<?php 
	
	if(isset($_POST['byRegion'])) {

	    // collect input data
	     $region = $_POST['region'];

	     if (!empty($region)){
	     	$region = prepareInput($region);
	     	findByRegion($region);
	     }
	}

	function findByRegion($region){
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}

		$query = oci_parse($conn, "SELECT rId, rName, rCuisine, rPhone, rAddress FROM Restaurant where upper(rRegion) = upper(:region)");

		oci_bind_by_name($query, ':region', $region);

		// Execute the query
		oci_execute($query);

		$table12 =  " 	<div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Restaurant Id</th>
						<th>Name</th> 
						<th>Cuisine</th>
						<th>Phone</th>
						<th>Address</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table12 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> <td> $row[3] </td> <td> $row[4] </td> </tr>";
		}
		
		$table12 .= "</table> </div>";
		
		echo "<script>";
		echo "table12 = " . json_encode($table12); 
		echo "</script>";

		OCILogoff($conn);
	}

?>




<?php
	function showRestaurants() {

		//connect to your database. Type in your username, password and the DB path
		$conn = oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
	        exit;
		}
	  
		$query = oci_parse($conn, "SELECT rId, rName, rCuisine FROM Restaurant");

		$table15 =  " <div class='container'>
				<table class='table table-striped table-bordered'>
				<tr>
					<th>Restaurant ID</th>
					<th>Name</th> 
					<th>Cuisine</th>
				</tr>
			";

		// Execute the query
		oci_execute($query);

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table15 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </tr>";
		}

		$table15 .= "</table> </div>";

		echo "<script>";
		echo "table15 = " . json_encode($table15); 
		echo "</script>";

		
		OCILogoff($conn);
	}
?>

<script>
	if (table12 != "") {
		$(document).ready(function() {
			$("#q12").append(table12);
		});
	}

	if (table13 != "") {
		$(document).ready(function() {
			$("#q13").append(table13);
		});
	}

	if (table14 != "") {
		$(document).ready(function() {
			$("#q14").append(table14);
		});
	}

	if (table15 != "") {
		$(document).ready(function() {
			$("#q15").append(table15);
		});
	}
</script>


</body>
</html>
