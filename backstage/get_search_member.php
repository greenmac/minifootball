<?php
include_once(dirname(dirname(__FILE__)).'/link.php');
include_once(dirname(dirname(__FILE__)).'\function.php');

function Post_curl($url,$header,$postdata)
{
	$curl = curl_init($url);
	$options = array(
		CURLOPT_POST=>true,
		CURLOPT_SSL_VERIFYPEER=>false,	//一定要加不然會報錯
		CURLOPT_HEADER=>false,
		CURLOPT_HTTPHEADER=>$header,	//-h
		CURLOPT_POSTFIELDS=>$postdata,	//傳遞data -d
		CURLOPT_RETURNTRANSFER=>1  		//將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
	);

	curl_setopt_array($curl, $options);//陣列設定curl參數
	$result = curl_exec($curl);//輸出結果
	curl_close ($curl);

	//$data = json_decode($result,true);
	return $result;
}

$url='http://minisoccer.elidot-cloud.com/xmlapi/getXML.php';
$header=array();
// $postdata=array(
// 	"order"=>"search_member",
//   "mutipleCardNum"=>"1504607350363722,1504608107778843"
// );

$member="SELECT * from member";
$memberRe=$link->prepare($member);
$memberRe->execute();
$memberRw=$memberRe->fetchall(PDO::FETCH_ASSOC);

foreach($memberRw as $k=>$v)
{
	$card_num=$v['card_num'];
	$card_num_string[]=$card_num;
}
$card_numImplode=implode(",",$card_num_string);
// pre($card_numImplode);

$postdata=array(
	"order"=>"search_member",
  	"mutipleCardNum"=>$card_numImplode
);


// $Result=Post_curl($url,$header,$postdata);
// $analytic=simplexml_load_string($Result);
// pre($Result);
// pre($analytic);

// if(!empty($analytic))
// {
// 	foreach($analytic->search_member->list as $k=>$v)
// 	{
// 		// pre($v);

// 		$m_id=$v->m_id;
// 		$phone=$v->phone;
// 		$email=$v->email;

// 		pre($m_id);
// 		pre($phone);
// 		pre($email);
// 	}
// }
?>
