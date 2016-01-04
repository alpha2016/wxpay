<?php
ini_set('date.timezone','Asia/Shanghai');

require_once "../lib/WxPay.Api.php";

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$input = new WxPayUnifiedOrder();
$input->SetBody("hsdajdsagfyjdas,gyaw,gf bvdasf,b jsdaf mdas das gfbxzcncncncncncncncndvgs ");
$input->SetAttach("ahhahah ");
$input->SetOut_trade_no(MCHID.date("YmdHis"));
$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("1111");
$input->SetNotify_url("http://localhost/wxpay/example/notify.php");  //回调函数
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("123456789");   //native方式必须穿product_id
$result = WxPayApi::GetPayUrl($input);
$url2 = $result["code_url"];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>微信支付样例</title>
</head>
<body>
	<img alt="模式二扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>
</body>
</html>
