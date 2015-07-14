<?php
	include 'config.php';
	$url = "http://$myQIP/monitoring.cgi?Now=1432007883717&type=22";
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
	    		$qNextCall = substr($qNextCall, 0, 4);
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
	echo(json_encode($datas));
	// Log
	if($enableLog)
	{
		$log  = "getSvcStatus.php - Accessed on ".time().PHP_EOL;
		file_put_contents('accessLog.txt', $log, FILE_APPEND);
	}

?>