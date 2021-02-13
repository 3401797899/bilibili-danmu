<?php
header("Content-type:application/json");
function get($url){
	$ua = "Mozilla/5.0 (Windows NT 6.3; ) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36 Edg/81.0.416.62";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, $ua);
		curl_setopt($curl, CURLOPT_ENCODING, "gzip");
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}
$url = str_replace('m.bilibili.com','www.bilibili.com',$_GET['url']);
$result = get($url);
preg_match("/window\.__INITIAL_STATE__=\{(.*?)\};/", $result, $matches);
$preJson = str_replace("window.__INITIAL_STATE__=", "", $matches[0]);
$preJson = str_replace(";","",$preJson);
$dataArray = json_decode($preJson,true);
$epList = $dataArray['epList'];
for($i = 0;$i < count($epList);$i++){
	$return[] = [
	'cid' => $epList[$i]['cid'],
	'name' => $epList[$i]['titleFormat'].$epList[$i]['longTitle'].$epList[$i]['badge'],
	'cover' => "https:".$epList[$i]['cover'],
	'comment' => "https://comment.bilibili.com/".$epList[$i]['cid'].".xml"
	];

}

echo json_encode($return,JSON_UNESCAPED_UNICODE);

?>
