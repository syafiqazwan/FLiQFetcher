
<?php
	$url = "http://192.168.1.20/monitoring.cgi?Now=1432007883717&type=24";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
	$dataString = curl_exec($ch);
	$dataString = substr($dataString, 2);
	// echo $output;
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
	    		$counter = $token;
	    	}
	    	else if($j == 1)
	    	{
	    		$status = $token;
	    	}
	    	else if($j == 2)
	    	{
	    		$staffName = $token;
	    	}
	    	else if($j == 3)
	    	{
	    		$service = $token;
	    	}
	    	else if($j == 4)
	    	{
	    		$crServing = $token;
	    	}
	    	else if($j == 5)
	    	{
	    		$opDur = $token;
	    	}
	    	else if($j == 6)
	    	{
	    		$qWaiting = $token;
	    	}
	    	else if($j == 7)
	    	{
	    		$qTransaction = $token;
	    	}
	    	else if($j == 8)
	    	{
	    		$qUnattended = $token;
	    	}
	    	else if($j == 9)
	    	{
	    		$avgDur = $token;
	    	}
	    	else if($j == 10)
	    	{
	    		$currentDur = $token;
	    	}
	    	$j++;

		    // echo "$token<br>";
		    $token = strtok("^");
	    }

	    $data = array('counter' => $counter,
	    			  'status' => $status,
	    			  'staffName' => $staffName,
	    			  'service' => $service,
	    			  'crServing' => $crServing,
	    			  'opDur' => $opDur,
	    			  'qWaiting' => $qWaiting,
	    			  'qTransaction' => $qTransaction,
	    			  'qUnattended' => $qUnattended,
	    			  'avgDur' => $avgDur,
	    			  'currentDur' => $currentDur
	    			  );

	    array_push($datas, $data);
	}

	array_pop($datas);
	// print_r($datas);
	echo(json_encode($datas));

?>