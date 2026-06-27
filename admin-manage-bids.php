<?php

require('system/config.php');

if(!empty($_GET['action'])){

	$bid = $_GET['bid'];
	$pid = $_GET['pid'];
	$action = $_GET['action'];

	$result = managePropertyBids($pid,$bid,$action);

	if($result){
		header("Location: admin-bids.php?bid_action=".$action);
	}
}


?>