<?php

$strAccessToken = "pvshntZ6AyDG9yEbGFQqR++VTaVTyMn+ibQRRMz8+JP2wNrO7eSBiWE9olx2uK8uAIQkVRVJxKYIIPujGMaV2xcdavFKcPICfpAcAORw2BxT4Ku/aYQLCeXaIGVFnCE5ipvqGs9eSxgf2gfNoreKCgdB04t89/1O/w1cDnyilFU=";
 
$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
 
$strUrl = "https://api.line.me/v2/bot/message/reply";
 
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";

$_msg = $arrJson['events'][0]['message']['text'];
$userid = $arrJson['events'][0]['source']['userId'];
 
 
$api_key="-O3pzxmDdrITsFlTnMCbWgsvqATaohmC";
$url = 'https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'';
$json = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"question":"'.$_msg.'"}');
$data = json_decode($json);
$isData=sizeof($data);

$jsonchk = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"userid":"'.$userid.'"}');
$datachk = json_decode($jsonchk);
$isDatachk = sizeof($datachk);
$idchk='';
 foreach($datachk as $rec){
      $idchk = $rec->userid;
   }

$jsonkey = file_get_contents('https://api.mlab.com/api/1/databases/edo_bot/collections/linebot?apiKey='.$api_key.'&q={"key":"'.$_msg.'"}');
$datakry = json_decode($jsonkey);
$isDatakey = sizeof($datakry);
$key='';
 foreach($datakry as $rec){
      $key = $rec->key;
   }

if (strpos($_msg, 'edo') !== false) {
  if (strpos($_msg, 'edo') !== false) {
    $x_tra = str_replace("edo","", $_msg);
    $pieces = explode("|", $x_tra);
    $_question=str_replace("[","",$pieces[0]);
    $_answer=str_replace("]","",$pieces[1]);
    //Post New Data
    $newData = json_encode(
      array(
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
}else if($_msg == $key  && $idchk != $userid && $isDatachk == null ){
    $newData = json_encode(
      array(
        'userid' => $userid,
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
    $arrPostData['messages'][0]['text'] = 'Key ของคุณถูกเปิดใช้ การโต้ตอบของผมต่อไปนี้มาจากผู้สร้างผมเพียงคนเดียว และคุณสามารถออกจากระบบได้เพียงพิมพ์คำว่า ออกอีโด้';
}

else if($_msg == $key && $isDatachk == null ){
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'มีคนใช้ key นี้ไปแล้วไม่สามารถใช้ได้อีก';
}

else if($isDatachk >0){
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = "key : $key userchk : $idchk ";
    sentdata();
}
else if (strpos($_msg, 'addkey') !== false) {
  if (strpos($_msg, 'addkey') !== false) {
    $x_tra = str_replace("addkey","", $_msg);
    $pieces = explode("]", $x_tra);
    $key=str_replace("[","",$pieces[0]);
   
    //Post New Data
    $newData = json_encode(
      array(       
        'key' => $key
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
    $arrPostData['messages'][0]['text'] = "addkey $key ok";
   
  }
}

else{
  if($isData >0){
   foreach($data as $rec){
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = $rec->answer;
   }
  }else{
    $arrPostData = array();
    $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
    $arrPostData['messages'][0]['type'] = "text";
    $arrPostData['messages'][0]['text'] = 'สอน edo ให้ฉลาดขึ้นพียงพิม: edo[คำถาม|ตอบ]' ;
  }
}
 
public function sentdata()
{
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
}
?>
