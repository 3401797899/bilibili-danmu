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

function xmlToJson($xmlContent){
	$query = "/<d.*?p=\"(.*?)\".*?>(.*?)<\/d>/";
	preg_match_all($query, $xmlContent, $matches);
	$result['code'] = 0;
	for($i = 0;$i < count($matches[1]);$i++){
		$p = explode(",", $matches[1][$i]);
		if($p[1] == "4"){
			$p[1] = "2";
		}elseif($p[1] == '5'){
			$p[1] = '1';
		}elseif($p[1] == '1'){
			$p[1] = '0';
		}
		$result['data'][] = [
		round($p[0],3),
		intval($p[1]),
		intval($p[3]),
		$p[6],
		$matches[2][$i]
		];
	}

	return $result;
}
$xmlContent = get("https://comment.bilibili.com/".$_GET['cid'].".xml");
$result = xmlToJson($xmlContent);
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
