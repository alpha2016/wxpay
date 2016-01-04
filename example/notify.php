<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';


class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	/**
	* 回调函数可以先写一个小功能，然后
	*/
	public function NotifyProcess($data, &$msg)
	{
		$xml = "<xml>
		  <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
		  <attach><![CDATA[支付测试]]></attach>
		  <bank_type><![CDATA[CFT]]></bank_type>
		  <fee_type><![CDATA[CNY]]></fee_type>
		  <is_subscribe><![CDATA[Y]]></is_subscribe>
		  <mch_id><![CDATA[10000100]]></mch_id>
		  <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
		  <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
		  <out_trade_no><![CDATA[1409811653]]></out_trade_no>
		  <result_code><![CDATA[SUCCESS]]></result_code>
		  <return_code><![CDATA[SUCCESS]]></return_code>
		  <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
		  <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
		  <time_end><![CDATA[20140903131540]]></time_end>
		  <total_fee>1</total_fee>
		  <trade_type><![CDATA[JSAPI]]></trade_type>
		  <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
		</xml>";
		// 将xml转成数组格式
		$data = json_decode(json_encode((array) simplexml_load_string($xml)), true);

		$notfiyOutput = array();
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

$notify = new PayNotifyCallBack();
$notify->Handle(false);
