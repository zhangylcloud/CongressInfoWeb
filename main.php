<?php 
    header("Access-Control-Allow-Origin:* ");
    header("Content-Type:application/json");
    date_default_timezone_set('America/Los_Angeles');

	if(isset($_GET['legislators'])){
        //$url = 'https://congress.api.sunlightfoundation.com/legislators?per_page=all&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
		$url = 'http://104.198.0.197:8080/legislators?per_page=all&apikey=0a0ed38f64bc40629c4df4cd22a23f27';

		$jsondata=file_get_contents($url);
		echo $jsondata;
    }
	else if(isset($_GET['bills'])){
		if(!isset($_GET['sponsor_id']) && isset($_GET['active'])){//get all bills
			//$url = 'https://congress.api.sunlightfoundation.com/bills?per_page=50&history.active=true&last_version.urls.pdf__exists=true&order=introduced_on&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
			$url = 'http://104.198.0.197:8080/bills?per_page=50&history.active=true&last_version.urls.pdf__exists=true&order=introduced_on&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
		}
		else if(!isset($_GET['sponsor_id']) && !isset($_GET['active'])){
			//$url = 'https://congress.api.sunlightfoundation.com/bills?per_page=50&history.active=false&last_version.urls.pdf__exists=true&order=introduced_on&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
			$url = 'http://104.198.0.197:8080/bills?per_page=50&history.active=false&last_version.urls.pdf__exists=true&order=introduced_on&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
		}
		else{//get 5 bills that is sponsored by particular legislator 
			//$url = 'https://congress.api.sunlightfoundation.com/bills?sponsor_id='.$_GET['sponsor_id'].'&per_page=5&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
			$url = 'http://104.198.0.197:8080/bills?sponsor_id='.$_GET['sponsor_id'].'&per_page=5&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
		}
        $jsondata=file_get_contents($url);
		echo $jsondata;
    }
	else if(isset($_GET['committees'])){
		if(!isset($_GET['member_ids'])){//get all committees
			//$url = 'https://congress.api.sunlightfoundation.com/committees?order=committee_id&per_page=all&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
			$url = 'http://104.198.0.197:8080/committees?order=committee_id&per_page=all&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
		}
		else{//get 5 committees a legistor is in
			//$url = 'https://congress.api.sunlightfoundation.com/committees?member_ids='.$_GET['member_ids'].'&per_page=5&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
			$url = 'http://104.198.0.197:8080/committees?member_ids='.$_GET['member_ids'].'&per_page=5&apikey=0a0ed38f64bc40629c4df4cd22a23f27';
		}
		$jsondata=file_get_contents($url);
		echo $jsondata;
	}
	
?>