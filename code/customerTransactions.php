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
		   	<a href="http://students.engr.scu.edu/~tharnlas/FoodRun/customerTransactions.php?showCust=true">Show all customers ordered by region.</a>
			</div>
			<p id="q"> 


		    <div class="well well-sm cust">
		   	<p>Show the order history of a given customer for a given period of time (input two dates).</p>
		   	<br>
		   	<form method="post" action="customerTransactions.php">
		   		<label>Customer ID: </label>
		   		<input type="text" name="customerID" id="customerID">
		   		<label>&nbsp Start Date: </label>
				<input type="text" name="startDate1" placeholder="e.g. 01/01/2017">
				<label>&nbsp End Date: &nbsp </label>
				<input type="text" name="endDate1" placeholder="e.g. 01/01/2017">
				<input type="submit" name="orderHistory">
		   	</form>
			</div>
			<p id="q6">

		 	
		 	<div class="well well-sm cust">
		   	<p>Show all customers who have ordered at least once from a specified restaurant.</p>
		   	<br>
		   	<form method="post" action="customerTransactions.php">
		   		<label>Restaurant: &nbsp</label>
		   		<input type="text" name="restaurant" id="restaurant" size="30">
		   		<input type="submit" name="byRestaurant">
		   	</form>
		  	</div>
		  	<p id="q7">
		  	
		  	<div class="well well-sm">
		   	<a href='http://students.engr.scu.edu/~tharnlas/FoodRun/customerTransactions.php?spent250=true'>Show all customers who spent $250 or more in the past three months.</a>
		   	</div>
		   	<p id="q8">
		   	

		 	<div class="well well-sm cust">
		   	<p>Show the customer name and phone number for the customer who made the most number of orders in a given month in the current year.</p>
		   	<br>
		   	<form method="post" action="customerTransactions.php">
		   		<label> Month: </label>
		   		<select name="month">
		   			<option value="Jan">Jan</option>
		   			<option value="Feb">Feb</option>
		   			<option value="Mar">Mar</option>
		   			<option value="Apr">Apr</option>
		   			<option value="May">May</option>
		   			<option value="June">June</option>
		   			<option value="July">July</option>
		   			<option value="Aug">Aug</option>
		   			<option value="Sep">Sep</option>
		   			<option value="Oct">Oct</option>
		   			<option value="Nov">Nov</option>
		   			<option value="Dec">Dec</option>
		   		</select>
		   		&nbsp&nbsp
		   		<input type="submit" name="mostOrdersInMonth">
		   	</form>
		   	</div>
		   	<p id="q9"></p>

		   	<div class="well well-sm cust">
		 	<p>Show the average price that customers pay on their order for a given region in a specified month. </p>
		 	<br>
		   	<form method="post" action="customerTransactions.php">
		   		<label> Region:</label>
		   		<input list="regions" name="regionForAvg"  pattern="San Jose|Santa Clara|Fremont|Palo Alto|San Francisco">
		   		<datalist id="regions">
		   			<option value="Fremont">
		   			<option value="Palo Alto">
		   			<option value="San Jose">
		   			<option value="Santa Clara">
		   			<option value="San Francisco">
		   		</datalist>
		   		<label> &nbsp Month:</label>
		   		<select name="monthForAvg">
		   			<option value="Jan">Jan</option>
		   			<option value="Feb">Feb</option>
		   			<option value="Mar">Mar</option>
		   			<option value="Apr">Apr</option>
		   			<option value="May">May</option>
		   			<option value="June">June</option>
		   			<option value="July">July</option>
		   			<option value="Aug">Aug</option>
		   			<option value="Sep">Sep</option>
		   			<option value="Oct">Oct</option>
		   			<option value="Nov">Nov</option>
		   			<option value="Dec">Dec</option>
		   		</select>
		   		&nbsp&nbsp
		   		<input type="submit" name="avgSpent">
		   	</form>
		   	</div>
		   	<p id="q10"></p>

		   	<div class="well well-sm cust">
		    <p>Show the total number of orders fulfilled in a certain time period (input two dates).</p>
		    <br>
			<form method="post" action="customerTransactions.php">
				<label>Start Date: &nbsp</label>
				<input type="text" name="startDate2" placeholder="e.g. 01/01/2017">
				<label>&nbsp End Date: &nbsp </label>
				<input type="text" name="endDate2" placeholder="e.g. 01/01/2017">
				<input type="submit" name="ordersByPeriod">
			</form>
		   	</div>
		   	<p id="q11"></p>
		</div>

	   	<script>
	   		var table = "", table6 = "", table7 = "", table8 = "", table9 = "", table10 = "", table11 = "";
	   	</script>


<!-- Show customers QUERY -->
<?php

	if(isset($_GET['showCust'])) {
		showCustomers();
	}

	function showCustomers() {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "SELECT cId, cName, cRegion
								   FROM Customers
								   ORDER BY cRegion"
						  );

		// Execute the query
		oci_execute($query);

		$table = " 	<div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Customer ID</th>
					    <th>Name</th>
					    <th>Region</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> </tr>";
		}

		$table .= "</table> </div>";

		echo "<script>";
		echo "table = " . json_encode($table);
		echo "</script>";

		OCILogoff($conn);
	}

?>





<!-- QUERY 11 -->
<?php

	function prepareInput($inputData){
		$inputData = trim($inputData);
		$inputData = htmlspecialchars($inputData);
		return $inputData;
	}

?>

<?php 

	if(isset($_POST['ordersByPeriod'])) {
	   		$startDate2 = $_POST['startDate2'];
	   		$endDate2 = $_POST['endDate2'];

	   		if (!empty($startDate2) && !empty($endDate2)) {
		     		$startDate2 = prepareInput($startDate2);
		     		$endDate2 = prepareInput($endDate2);
		     		ordersByPeriod($startDate2, $endDate2);
			}
	}

	function ordersByPeriod($startDate2, $endDate2) {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "SELECT count(orderID)
    							   FROM Orders
    							   WHERE time_delivered between TO_DATE(:startDate2,'mm/dd/yyyy') AND TO_DATE(:endDate2,'mm/dd/yyyy')"
						  );

		oci_bind_by_name($query, ':startDate2', $startDate2);
		oci_bind_by_name($query, ':endDate2', $endDate2);

		// Execute the query
		oci_execute($query);

		$table11 = " 	<div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Total orders</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table11 .= "<tr> <td> $row[0] </td> </tr>";
		}

		$table11 .= "</table> </div>";

		echo "<script>";
		echo "table11 = " . json_encode($table11);
		echo "</script>";

		OCILogoff($conn);
	}
?>



<!-- QUERY 10 -->
<?php 
	if(isset($_POST['avgSpent'])) {
		$monthForAvg = $_POST['monthForAvg'];
		$monthForAvg = prepareInput($monthForAvg);

		$regionForAvg = $_POST['regionForAvg'];
		$regionForAvg = prepareInput($regionForAvg);

		avgSpent($monthForAvg, $regionForAvg);
	}


	function avgSpent($monthForAvg, $regionForAvg) {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "SELECT avg(total)
    				               FROM Orders natural join Customers
    							   WHERE upper(TO_CHAR(time_delivered, 'MON')) = upper(:monthForAvg)  AND upper(cRegion) = upper(:regionForAvg) "
						  );

		oci_bind_by_name($query, ':monthForAvg', $monthForAvg);
		oci_bind_by_name($query, ':regionForAvg', $regionForAvg);

		// Execute the query
		oci_execute($query);

		$table10 = " 	<div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Average Spent</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table10 .= "<tr> <td>$row[0]</td> </tr>";
		}

		$table10 .= "</table> </div>";
		
		echo "<script>";
		echo "table10 = " . json_encode($table10);
		echo "</script>";

		OCILogoff($conn);
	}
?>


<!-- QUERY 9 -->
<?php 
	if(isset($_POST['mostOrdersInMonth'])) {
		$month = $_POST['month'];
		$month = prepareInput($month);
		mostOrdersInMonth($month);
	}

	function mostOrdersInMonth($month) {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "SELECT cName, cPhone, count(orderId) no_of_orders
								   FROM Orders natural join Customers
								   WHERE upper(TO_CHAR(time_delivered, 'MON')) = upper(:month)
								   		 AND EXTRACT(YEAR FROM time_delivered) = EXTRACT(YEAR FROM SYSDATE)
								   GROUP BY cName, cPhone
              					   HAVING count(orderId) = (SELECT max(count(orderid))
              					   						    FROM Orders 
              					   						    WHERE upper(TO_CHAR(time_delivered, 'MON')) = upper(:month)
								   		 					AND EXTRACT(YEAR FROM time_delivered) = EXTRACT(YEAR FROM SYSDATE)
								   							GROUP BY cid
								   							)"
						  );

		oci_bind_by_name($query, ':month', $month);

		// Execute the query
		oci_execute($query);

		$table9 =  " 	<div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Customer Name</th>
						<th>Phone Number</th> 
						<th>Number of Orders</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table9 .= "<tr> <td>$row[0]</td> <td>$row[1]</td> <td>$row[2]</td> </tr>";
		}

		$table9 .= "</table> </div>";
		
		echo "<script>";
		echo "table9 = " . json_encode($table9);
		echo "</script>";

		OCILogoff($conn);
	}
?>


<!-- QUERY 8 -->
<?php
	if (isset($_GET['spent250'])) {
	   spent250();
	}

	function spent250() {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "SELECT cName, cPhone, sum(total)
           					       FROM Customers natural join Orders
          						   WHERE EXTRACT(MONTH FROM time_made) between 
        						  		(EXTRACT(MONTH FROM SYSDATE) - 3) AND EXTRACT(MONTH FROM SYSDATE)
          						   GROUP BY cid, cName, cPhone
          						   HAVING sum(total) >= 250"
						  );

		// Execute the query
		oci_execute($query);

		$table8 = " <div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Name</th> 
						<th>Phone</th>
						<th>Amount Spent</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table8 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> </tr>";
		}

		$table8 .= "</table> </div>";
		
		echo "<script>";
		echo "table8 = " . json_encode($table8);
		echo "</script>";

		OCILogoff($conn);
	}
?>


<!-- QUERY 7 -->
<?php

	if(isset($_POST['byRestaurant'])) {
		$restaurant = $_POST['restaurant'];

 		if (!empty($restaurant)) {
			$restaurant = prepareInput($restaurant);
			findByRestaurant($restaurant);
		}
	}

	function findByRestaurant($restaurant){
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "SELECT cId, cName, cPhone 
								   FROM Customers natural join Orders 
		    					   WHERE orderId in (SELECT orderId FROM OrderLine natural join Restaurant where upper(rName) = upper(:restaurant))");

		oci_bind_by_name($query, ':restaurant', $restaurant);

		// Execute the query
		oci_execute($query);

		$table7 = " 	<div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Customer Id</th>
						<th>Name</th> 
						<th>Phone</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table7 .= "<tr> <td> $row[0] </td> <td> $row[1] </td> <td> $row[2] </td> </tr>";
		}

		$table7 .= "</table> </div>";

		echo "<script>";
		echo "table7 = " . json_encode($table7);
		echo "</script>";


		OCILogoff($conn);
	}
?>



<!-- QUERY 6 -->
<?php 
	if(isset($_POST['orderHistory'])) {
		$customerID = $_POST['customerID'];
 		$startDate1 = $_POST['startDate1'];
 		$endDate1 = $_POST['endDate1'];

 		if (!empty($customerID) && !empty($startDate1) && !empty($endDate1)) {
 			$customerID = prepareInput($customerID);
 			$customerID = str_pad($customerID, 5);

 			$startDate1 = prepareInput($startDate1);
 			$endDate1 = prepareInput($endDate1);

 			orderHistory($customerID, $startDate1, $endDate1);
 		}	
	}		

	function orderHistory($customerID, $startDate1, $endDate1) {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "SELECT cId, orderId, time_made, total, empId, time_delivered
        						   FROM Orders
								   WHERE upper(cID) = upper(:customerID) and time_delivered between TO_DATE(:startDate1,'mm/dd/yyyy') AND TO_DATE(:endDate1,'mm/dd/yyyy')"
						  );

		oci_bind_by_name($query, ':customerID', $customerID);
		oci_bind_by_name($query, ':startDate1', $startDate1);
		oci_bind_by_name($query, ':endDate1', $endDate1);

		// Execute the query
		oci_execute($query);

		$table6 = " 	<div class='container'>
					<table class='table table-striped table-bordered'>
					<tr>
						<th>Customer ID</th>
						<th>Order ID</th> 
						<th>Time Made</th>
						<th>Total</th>
						<th>Delivery Driver</th>
						<th>Time Delivered</th>
					</tr>
				";

		while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
			$table6 .= "<tr> <td>$row[0]</td> <td>$row[1]</td> <td>$row[2]</td> <td>$row[3]</td> <td> $row[4] </td> <td>$row[5]</td> </tr>";
	}

		$table6 .= "</table> </div>";
		
		echo "<script>";
		echo "table6 = " . json_encode($table6);
		echo "</script>";


		OCILogoff($conn);
	}

?>

<script>
	if (table != "") {
		$(document).ready(function() {
			$("#q").append(table);
		});
	}

	if (table6 != "") {
		$(document).ready(function() {
			$("#q6").append(table6);
		});
	}

	if (table7 != "") {
		$(document).ready(function() {
			$("#q7").append(table7);
		});
	}

	if (table8 != "") {
		$(document).ready(function() {
			$("#q8").append(table8);
		});
	}

	if (table9 != "") {
		$(document).ready(function() {
			$("#q9").append(table9);
		});
	}

	if (table10 != "") {
		$(document).ready(function() {
			$("#q10").append(table10);
		});
	}

	if (table11 != "") {
		$(document).ready(function() {
			$("#q11").append(table11);
		});
	}

</script>

</body>
</html>

