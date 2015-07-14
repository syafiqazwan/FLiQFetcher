<?php
	include 'config.php';
	$params = json_decode(file_get_contents('php://input'), true);
	if(isset($params))
	{
		$qNum = $params['qNum'];
		// echo "WS echo qNum: $qNum <br/>";

		// Get data from myQServer
		$url = "http://$myQIP/monitoring.cgi?Now=1432007883717&type=26";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
		$dataString = curl_exec($ch);
		$dataString = substr($dataString, 2);
		// echo $dataString;
		// echo $output;
		curl_close($ch);

		$dataArray = explode("|",$dataString);
		// print_r($dataArray);
		$datas = array();

		// Convert the retrieved data to array
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
		    		$qNumber = $token;
		    	}
		    	else if($j == 1)
		    	{
		    		$mainSvc = $token;
		    	}
		    	else if($j == 2)
		    	{
		    		$svc = $token;
		    	}
		    	else if($j == 3)
		    	{
		    		$issueType = $token;
		    	}
		    	else if($j == 4)
		    	{
		    		$counter = $token;
		    	}
		    	else if($j == 5)
		    	{
		    		$issueTime = $token;
		    	}
		    	else if($j == 6)
		    	{
		    		$waitDur = $token;
		    	}

		    	$j++;

			    // echo "$token<br>";
			    $token = strtok("^");
		    }

		    $data = array('qNumber' => $qNumber,
		    			  'mainSvc' => $mainSvc,
		    			  'svc' => $svc,
		    			  'issueType' => $issueType,
		    			  'counter' => $counter,
		    			  'issueTime' => $issueTime,
		    			  'waitDur' => $waitDur
		    			  );

		    // print_r($data);
		    array_push($datas, $data);
		}

		array_pop($datas);
		// print_r($datas);
		// echo "qNum Entered: $qNum <br/>";
		$userRow;
		for($x = 0; $x < count($datas); $x++)
		{
			$chckArray = $datas[$x];
			if($chckArray['qNumber'] == $qNum)
			{
				$userRow = $datas[$x];
			}
			else
			{
				// echo "FALSEEEE \n";
			}
		}

		// print_r($userRow);

		if(!empty($userRow))
		{
			// print_r($userRow);
			// echo "Queue Number: ".$userRow['qNumber']."<br/>";
			// echo "Main Service: ".$userRow['mainSvc']."<br/>";
			// echo "Issued On: ".$userRow['issueTime']."<br/>";
			// echo "Estimated Waiting Time: <br/>";
			// Convert the array data to JSON
			echo(json_encode($userRow));
		}
		else
		{
			echo "Queue Number $qNum Not Found";
		}

	}
	// Log
	if($enableLog)
	{
		$log  = "checkQNum.php - Accessed on ".time().PHP_EOL;
		file_put_contents('accessLog.txt', $log, FILE_APPEND);
	}

?>