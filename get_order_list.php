<?php
$m_id=isset($_GET["m_id"]) && is_numeric($_GET["m_id"]) ? $_GET["m_id"]:0;

$pro_url='http://linky-id.com/xmlapi/getXML.php';
$test_url='http://minisoccer.elidot-cloud.com/xmlapi/getXML.php';

$card_num="";
$name="";
$getOrderInfo=array();
$MDResult=Post_curl($test_url,array(),array('order'=>'get_user_data','m_id'=>$m_id));
$MDanalytic=simplexml_load_string($MDResult);


if(!empty($MDanalytic))
{
	$m_id=isset($MDanalytic->get_user_data->m_id) ? (int) $MDanalytic->get_user_data->m_id:0;
	$card_num=isset($MDanalytic->get_user_data->card_num) ? (string) $MDanalytic->get_user_data->card_num:0;
	$name=isset($MDanalytic->get_user_data->name) ? (string) $MDanalytic->get_user_data->name:"";
}

if(empty($m_id)){echo 'error' ;exit;}

//$url='http://dev12.elidot.com.tw:125/xml_api.php';
$url='www.appmakertw.com/xml_api.php';
$header=array();
$postdata=array(
	"order"=>"get_orders_list",
	"app_api_key"=>"test_685",
	"start"=>0,
	"page"=>999,
	"sort_by"=>1,  //0 asc 1 desc
	"m_id"=>$m_id,
	"card_num"=>$card_num,
	"order_status"=>"S",
	"shipping_status"=>"-1"
);

//pre($postdata);

$getOrderInfo=array();
$Result=Post_curl($url,$header,$postdata);

if(!empty($Result))
{
	$analytic=simplexml_load_string($Result);

	$sum=$analytic->sum;

	if(!empty($analytic)&&$sum>0)
	{
		$c=0;
		foreach($analytic->get_orders_list->list as $v)
		{
			$sku='';
			$plu='';
			$product_name='';
			$order_number=(string) $v->order_number;
			$oid=$v->oid;

			//因商品筆數會有所不同, 需要多跑一次迴圈
			$remainOrder=array();
			$spe_prix="";
			$spe_count=0;

			for($i=0;$i<sizeof($v->list_content);$i++)
			{
				$sku=(string) $v->list_content[$i]->sku;
				$plu=(string) $v->list_content[$i]->plu;
				$product_name=(string) $v->list_content[$i]->product_name;
				$product_amount=(int) $v->list_content[$i]->product_amount;

				for($j=0;$j<$product_amount;$j++)
				{
					//因應原先已有資料, 故多加上前綴字區隔
					if(!in_array( (string) $v->order_number ,$remainOrder))
					{
						$spe_prix="";
					}
					else
					{
						$spe_count++;
						$spe_prix="-".$spe_count;
					}

					$getOrderInfo[$c]=array(
						"card_num"		=>(string) $v->card_num,
						"member_name"	=>(string) $name,
						"order_num"		=>(string) $v->order_number.$spe_prix,
						"product_name"  => $product_name,
						"sku"			=> $sku,
						"plu"			=> $plu,
					);

					$remainOrder[]=(string)$v->order_number;

					$c++;
				}
			}
		}
	}
}

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

?>
