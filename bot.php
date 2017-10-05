<?php
//Token Line
$strAccessToken = "pvshntZ6AyDG9yEbGFQqR++VTaVTyMn+ibQRRMz8+JP2wNrO7eSBiWE9olx2uK8uAIQkVRVJxKYIIPujGMaV2xcdavFKcPICfpAcAORw2BxT4Ku/aYQLCeXaIGVFnCE5ipvqGs9eSxgf2gfNoreKCgdB04t89/1O/w1cDnyilFU=";
 //key Mlab
$api_key="-O3pzxmDdrITsFlTnMCbWgsvqATaohmC";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);

$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
$_msg = $arrJson['events'][0]['message']['text'];
$userid = $arrJson['events'][0]['source']['userId'];

$where_key =$userid."key";


//get data public user
$url = 'https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$_msg.'","userid":"all","key":"all"}');
$data = json_decode($json);
$isData=sizeof($data);

 	$array_data = json_decode($json,true);
	$ran = rand(0,count($array_data)-1);
 	$datalane = $array_data[$ran];

//get data private user
$jsonpivate = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$_msg.'","userid":"'.$userid.'"}');
$datapivate = json_decode($jsonpivate);
$isDatapivate=sizeof($datapivate); 

//get data check user ID
$jsonchk = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"userid":"'.$where_key.'"}');
$datachk = json_decode($jsonchk);
$isDatachk = sizeof($datachk);
foreach($datachk as $rec){
	$idchk = $rec->userid;
}

//get data check Key ID
$jsonkey = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"key":"'.$_msg.'"}');
$datakry = json_decode($jsonkey);
$isDatakey = sizeof($datakry);
foreach($datakry as $rec){
	$key = $rec->key;
}

// chk key use
$jsonkey_use = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"key_use":"'.$_msg.'"}');
$datakry_use = json_decode($jsonkey_use);
$isDatakey_use = sizeof($datakry_use);

// chk user private msg
$jsonkey_msg = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"userid":"'.$where_key.'"}');
$datakry_msg = json_decode($jsonkey_msg);
$isDatakey_msg = sizeof($datakry_msg);
foreach($datakry_msg as $rec){
	$key_msg = $rec->key;
}

///////////////////////////////////////////// add msg public
if (strpos($_msg, 'edo') !== false) {
	if (strpos($_msg, 'edo') !== false) {
		$x_tra = str_replace("edo","", $_msg);
		$pieces = explode("|", $x_tra);
		$_question=str_replace("[","",$pieces[0]);
		$_answer=str_replace("]","",$pieces[1]);
    //Post New Data
		$newData = json_encode(
			array(
				'key' => 'all',
				'userid' => 'all',
				'question' => $_question,
				'answer'=> $_answer
			)
		);
		$opts = array(
			'http' => array(
				'method' => "POST",
				'header' => "Content-type: application/json",
				'content' => $newData
			)
		);
		$context = stream_context_create($opts);
		$returnValue = file_get_contents($url,false,$context);
		$arrPostData = array();
		$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
		$arrPostData['messages'][0]['type'] = "text";
		$arrPostData['messages'][0]['text'] = 'ขอบคุณที่บอก edo';
	}
}
//////////////////////////////////////////////////////////////////////use key user
else if($_msg == $key && $isDatakey_use == 0){
	$newData = json_encode(
		array(
			'userid' => $userid."key",
			'key_use' => $_msg ,
			'key' => $_msg 
		)
	);
	$opts = array(
		'http' => array(
			'method' => "POST",
			'header' => "Content-type: application/json",
			'content' => $newData
		)
	);
	$context = stream_context_create($opts);
	$returnValue = file_get_contents($url,false,$context);
	$arrPostData = array();
	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
	$arrPostData['messages'][0]['type'] = "text";
	$arrPostData['messages'][0]['text'] = "Key ของคุณถูกเปิดใช้ การโต้ตอบของผมต่อไปนี้มาจากผู้สร้างผมเพียงคนเดียว และคุณสามารถออกจากระบบได้เพียงพิมพ์คำว่า ออกอีโด้ $datakry_use";
}
///////////////////////////////////////////chk use key
else if($isDatakey_use > 0){
	$arrPostData = array();
	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
	$arrPostData['messages'][0]['type'] = "text";
	$arrPostData['messages'][0]['text'] = "มีคนใช้ Key นี้แล้ว";
}
/////////////////////////////////////////// start loop private
else if($isDatachk >0)
{
	if (strpos($_msg, 'pri') !== false) {
		if (strpos($_msg, 'pri') !== false) {
			$x_tra = str_replace("pri","", $_msg);
			$pieces = explode("|", $x_tra);
			$_question=str_replace("[","",$pieces[0]);
			$_answer=str_replace("]","",$pieces[1]);
    //Post New Data
			$newData = json_encode(
				array(
					'key' => $key_msg,
					'userid' => $userid,
					'question' => $_question,
					'answer'=> $_answer
				)
			);
			$opts = array(
				'http' => array(
					'method' => "POST",
					'header' => "Content-type: application/json",
					'content' => $newData
				)
			);
			$context = stream_context_create($opts);
			$returnValue = file_get_contents($url,false,$context);
			$arrPostData = array();
			$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
			$arrPostData['messages'][0]['type'] = "text";
			$arrPostData['messages'][0]['text'] = 'ขอบคุณที่บอก edo';
		}
	}
 //////////////////////////////////////////////////////add key by private
	else if ($_msg == 'addkey') {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 17; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

    //Post New Data
		$newData = json_encode(
			array( 
				'showkey' => "yes",      
				'key' => $randomString
			)
		);
		$opts = array(
			'http' => array(
				'method' => "POST",
				'header' => "Content-type: application/json",
				'content' => $newData
			)
		);
		$context = stream_context_create($opts);
		$returnValue = file_get_contents($url,false,$context);
		$arrPostData = array();
		$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
		$arrPostData['messages'][0]['type'] = "text";
		$arrPostData['messages'][0]['text'] = $randomString;  
	}
///////////////////////////////////////////////////event private
	else
	{
		if($isDatapivate >0){
			foreach($datapivate as $rec){
				$arrPostData = array();
				$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
				$arrPostData['messages'][0]['type'] = "text";
				$arrPostData['messages'][0]['text'] = $rec->answer;
			}
		}
		else{
			$arrPostData = array();
			$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
			$arrPostData['messages'][0]['type'] = "text";
			$arrPostData['messages'][0]['text'] = "สอน edo ให้ฉลาดขึ้นพียงพิม: pri[คำถาม|ตอบ] " ;
		}   
	}
}
//////////////////////////////////////////////////////add key by public
else if ($_msg == 'addkey') {   
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < 17; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}

    //Post New Data
	$newData = json_encode(
		array(   
			'showkey' => "yes",       
			'key' => $randomString
		)
	);
	$opts = array(
		'http' => array(
			'method' => "POST",
			'header' => "Content-type: application/json",
			'content' => $newData
		)
	);
	$context = stream_context_create($opts);
	$returnValue = file_get_contents($url,false,$context);
	$arrPostData = array();
	$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
	$arrPostData['messages'][0]['type'] = "text";
	$arrPostData['messages'][0]['text'] = $randomString;  

}

/////////////////////////////////////////////////event public
else{
	if($isData >0){
		foreach($data as $rec){
			$arrPostData = array();
			$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
			$arrPostData['messages'][0]['type'] = "text";
			$arrPostData['messages'][0]['text'] = $rec->answer.$json ;
		}
	}else{
		$arrPostData = array();
		$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
		$arrPostData['messages'][0]['type'] = "text";
		$arrPostData['messages'][0]['text'] = 'สอน edo ให้ฉลาดขึ้นพียงพิม: edo[คำถาม|ตอบ]' ;
	}
}   

  //   $arrPostData = array();
  //  $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
  // $arrPostData['messages'][0]['type'] = "text";
  // $arrPostData['messages'][0]['text'] = $datalane ;

$channel = curl_init();
curl_setopt($channel, CURLOPT_URL,$strUrl);
curl_setopt($channel, CURLOPT_HEADER, false);
curl_setopt($channel, CURLOPT_POST, true);
curl_setopt($channel, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($channel, CURLOPT_RETURNTRANSFER,true);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($channel);
curl_close ($channel);
?>
