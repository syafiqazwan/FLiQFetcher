<?php
	include 'config.php';
	$url = "http://$myQIP/cfgsysget.cgi";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
	$dataString = curl_exec($ch);
	// echo $output;
	curl_close($ch);

	$dataArray = explode("|",$dataString);
	// print_r($dataArray);
	$myQIP = array("myQIPAddrs" => $dataArray[3],
				   "gateway" => $dataArray[4],
				   "subnetMask" => $dataArray[5]);

	echo json_encode($myQIP);
	// Log
	if($enableLog)
	{
		$log  = "getMyQIP.php - Accessed on ".time().PHP_EOL;
		file_put_contents('accessLog.txt', $log, FILE_APPEND);
	}
?>