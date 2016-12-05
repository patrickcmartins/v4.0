<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);	

require_once('CRMDefaults.php');
require_once('goCRMAPISettings.php');

// check required fields
$reason = "Unable to Modify List";
$validated = 1;
if (!isset($_POST["modifyid"])) {
	$validated = 0;
}

if ($validated == 1) {

	// collect new user data.	
	$modifyid = $_POST["modifyid"];
    
	$name = NULL; if (isset($_POST["name"])) { 
		$name = $_POST["name"]; 
		$name = stripslashes($name);
	}
	
	$desc = NULL; if (isset($_POST["desc"])) { 
		$desc = $_POST["desc"]; 
		$desc = stripslashes($desc);
	}
	
    $campaign = NULL; if (isset($_POST["campaign"])) { 
		$campaign = $_POST["campaign"]; 
		$campaign = stripslashes($campaign);
	}
	
    $status = NULL; if (isset($_POST["active"])) { 
		$status = $_POST["active"]; 
		$status = stripslashes($status);
	}
	$reset_list = NULL; if (isset($_POST["reset_list"])) { 
		$reset_list = $_POST["reset_list"]; 
		$reset_list = stripslashes($reset_list);
	}

	$url = gourl."/goLists/goAPI.php"; # URL to GoAutoDial API file
    $postfields["goUser"] = goUser; #Username goes here. (required)
    $postfields["goPass"] = goPass; #Password goes here. (required)
    $postfields["goAction"] = "goEditList"; #action performed by the [[API:Functions]]
    $postfields["responsetype"] = responsetype; #json (required)
    $postfields["list_id"] = $modifyid; #Desired list id. (required)
	$postfields["list_name"] = $name; #Desired value for user (required)
	$postfields["list_description"] = $desc; #Desired value for user (required)
	$postfields["campaign_id"] = $campaign; #Desired value for user (required)
	$postfields["active"] = $status; #Desired value for user (required)
	$postfields["reset_list"] = $reset_list;
    $postfields["hostname"] = $_SERVER['REMOTE_ADDR']; #Default value
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($data);
    //print_r($output);die;
    if ($output->result=="success") {
    # Result was OK!
		echo "success";
    } else {
    # An error occured
		echo $output->result;
        //$lh->translateText("unable_modify_list");
    }
    
} else { print $reason; }
?>
