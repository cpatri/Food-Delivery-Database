<?php


	function prepareInput($inputData){
			$inputData = trim($inputData);
			$inputData = htmlspecialchars($inputData);
			return $inputData;
	}

	if (isset($_POST['newEmp'])) {

		$first = $_POST['firstName']; 
		$last = $_POST['lastName'];
		$phone = $_POST['phone'];
		$region = $_POST['region'];
		$hours = $_POST['hours'];

		if (!empty($first) && !empty($last) && !empty($phone) && !empty($region) && !empty($hours)) {
			$name = $first . $last;
			$name = prepareInput($name);
			$phone = prepareInput($phone);
			$region = prepareInput($region);
			$hours = prepareInput($hours);
			newEmployee($name,$phone, $region, $hours);
		}
	}

	function newEmployee($name, $phone, $region, $hours)  {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "INSERT INTO Employees VALUES ('e'||emp_seq.nextval, :name,:region, :phone, TO_NUMBER(:hours,9.99))");

		oci_bind_by_name($query, ':name', $name);
		oci_bind_by_name($query, ':phone', $phone);
		oci_bind_by_name($query, ':region', $region);
		oci_bind_by_name($query, ':hours', $hours);

		// Execute the query
		$res = oci_execute($query);
		if ($res)
			echo '<br><br> <p style="color:green;font-size:25px">Data successfully inserted.</p>';
		else{
			$e = oci_error($query);
	        	echo $e['message'];
		}
		
		echo "<a style='font-size:18px;' href='http://students.engr.scu.edu/~tharnlas/FoodRun/contracts.html'> << Return to Contracts.</a>";
		OCILogoff($conn);
	}		
?>


<?php
	if (isset($_POST['newRest'])) {
		$rName = $_POST['rName']; 
		$cuisine = $_POST['cuisine'];
		$rPhone = $_POST['rPhone'];
		$rRegion = $_POST['rRegion'];
		$rAddress = $_POST['rAddress'];
		$rStart = $_POST['rStart'];

		if (!empty($rName) && !empty($cuisine) && !empty($rPhone) && !empty($rRegion) && !empty($rAddress)) {
			$rName = prepareInput($rName);
			$rPhone = prepareInput($rPhone);
			$rRegion = prepareInput($rRegion);
			$rAddress = prepareInput($rAddress);
			$cuisine = prepareInput($cuisine);
			newRestaurant($rName, $cuisine, $rPhone, $rAddress, $rRegion);
		}
	}

	function newRestaurant($rName, $cuisine, $rPhone, $rAddress, $rRegion)  {
		$conn=oci_connect('tharnlas', 'scu', '//dbserver.engr.scu.edu/db11g');
		if(!$conn) {
		     print "<br> connection failed:";
		    exit;
		}

		$query = oci_parse($conn, "INSERT INTO Restaurant
									VALUES ('r'||rest_seq.nextval, :rName, :cuisine, :rPhone, :rAddress, :rRegion, TO_DATE(:rStart,'mm/dd/yyyy'))");

		oci_bind_by_name($query, ':rName', $rName);
		oci_bind_by_name($query, ':rPhone', $rPhone);
		oci_bind_by_name($query, ':rRegion', $rRegion);
		oci_bind_by_name($query, ':rAddress', $rAddress);
		oci_bind_by_name($query, ':cuisine', $cuisine);
		oci_bind_by_name($query, ':rStart', $rStart);

		// Execute the query
		$res = oci_execute($query);
		if ($res)
			echo '<br><br> <p style="color:green;font-size:20px">Data successfully inserted.</p>';
		else{
			$e = oci_error($query);
	        	echo $e['message'];
		}
		
		echo "<a style='font-size:18px;' href='http://students.engr.scu.edu/~tharnlas/FoodRun/contracts.html'> << Return to Contracts.</a>";
		OCILogoff($conn);
	}	
?>


