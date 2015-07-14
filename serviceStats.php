<?php
	$url = "http://192.168.1.20/monitoring.cgi?Now=1432007883717&type=22";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
	$dataString = curl_exec($ch);
	$dataString = substr($dataString, 2);
	// echo $dataString;
	curl_close($ch);

	$dataArray = explode("|",$dataString);
	// print_r($dataArray);
	$datas = array();

	for($i = 0; $i < count($dataArray); $i++)
	{
		// echo $dataArray[$i]."<br/>";
		// echo "<br/> New Data $i <br/><br/>";
		$dString = $dataArray[$i];
		$token = strtok($dString, "^");

		$j = 0;
		while ($token !== false)
	    {
	    	if($j == 0)
	    	{
	    		$mainSvc = $token;
	    	}
	    	else if($j == 1)
	    	{
	    		$svc = $token;
	    	}
	    	else if($j == 2)
	    	{
	    		$counter = $token;
	    	}
	    	else if($j == 3)
	    	{
	    		$qLastServed = $token;
	    	}
	    	else if($j == 4)
	    	{
	    		$qNextCall = $token;
	    	}
	    	else if($j == 5)
	    	{
	    		$qNextIssue = $token;
	    	}
	    	else if($j == 6)
	    	{
	    		$tIssued = $token;
	    	}
	    	else if($j == 7)
	    	{
	    		$tWaiting = $token;
	    	}
	    	else if($j == 8)
	    	{
	    		$tTransaction = $token;
	    	}
	    	else if($j == 9)
	    	{
	    		$tUnattended = $token;
	    	}
	    	else if($j == 10)
	    	{
	    		$AWT = $token;
	    	}
	    	else if($j == 11)
	    	{
	    		$AWA = $token;
	    	}
	    	else if($j == 12)
	    	{
	    		$AST = $token;
	    	}
	    	else if($j == 13)
	    	{
	    		$ASA = $token;
	    	}
	    	$j++;

		    // echo "$token<br>";
		    $token = strtok("^");
	    }

	    $data = array('mainSvc' => $mainSvc,
	    			  'svc' => $svc,
	    			  'counter' => $counter,
	    			  'qLastServed' => $qLastServed,
	    			  'qNextCall' => $qNextCall,
	    			  'qNextIssue' => $qNextIssue,
	    			  'tIssued' => $tIssued,
	    			  'tWaiting' => $tWaiting,
	    			  'tTransaction' => $tTransaction,
	    			  'tUnattended' => $tUnattended,
	    			  'AWT' => $AWT,
	    			  'AWA' => $AWA,
	    			  'AST' => $AST,
	    			  'ASA' => $ASA
	    			  );

	    array_push($datas, $data);
	}

	array_pop($datas);
	// print_r($datas);
	// echo(json_encode($datas));

?>

<html>
<head>
	<title>Service Status</title>
</head>
<body>

	<?php
		$svcTable = "<table border='1'>
					<tr>
						<th> No </th>
						<th> Service </th>
						<th> Next Call </th>
						<th> Next Issue </th>
						<th> Waiting </th>
					</tr>";

		$b = 1;
		for($a = 0; $a < count($datas); $a++)
		{
			$svcName = $datas[$a]['mainSvc'];
			$nextCall = $datas[$a]['qNextCall'];
			$nextIssue = $datas[$a]['qNextIssue'];
			$waiting = $datas[$a]['tWaiting'];

			$svcTable .= "<tr>
							<td> $b </td>
							<td> $svcName </td>
							<td> $nextCall </td>
							<td> $nextIssue </td>
							<td> $waiting </td>
						</tr>";

			$b++;
		}

		$svcTable .= "</table>";
		echo $svcTable;
	?>

</body>
</html>