<html>
<head>
	<title>Queue Check</title>
</head>
<body>

	<form method="POST">
		<input type="text" name="qNumber">
		<input type="submit" name="submitVal" value="Check">
	</form>

</body>
</html>

<?php
	if(isset($_POST['submitVal']))
	{
		$qNum = $_POST['qNumber'];
		$url = "http://192.168.1.20/monitoring.cgi?Now=1432007883717&type=26";
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
			// echo "chckArray: $x ";
			// print_r($chckArray['qNumber']);
			// echo('<br/>');
			if(strpos($chckArray['qNumber'], $qNum) !== false)
			{
				// echo "Array: ".$chckArray['qNumber'];
				$userRow = $datas[$x];
				// echo "TRUEEEE <br/>";
			}
			else
			{
				// echo "FALSEEEE <br/>";
			}
		}

		// print_r($userRow);

		if(!empty($userRow))
		{
			// print_r($userRow);
			echo "Queue Number: ".$userRow['qNumber']."<br/>";
			echo "Main Service: ".$userRow['mainSvc']."<br/>";
			echo "Issued On: ".$userRow['issueTime']."<br/>";
			echo "Estimated Waiting Time: --:--:--<br/>";
		}
		else
		{
			echo "Queue Number $qNum Not Found";
		}
	}
?>