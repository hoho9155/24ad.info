<?php

class Twocheckout_Util extends Twocheckout
{
	static function returnResponse($contents, $format = null)
	{
		/*
		 * BeDigit Fix
		 */
		if (!is_array($contents) && !is_object($contents) && !isJson($contents)) {
			$message = "The 2Checkout API call response does not match the expected one.";
			$message .= "This usually happens when the merchant's account is not active or has been suspended.";
			$message .= '<br><br>';
			$message .= '<strong>Received response:</strong><br>' . htmlspecialchars($contents, ENT_QUOTES);
			$message .= '<br><br>';
			$message .= '-----<br>';
			$message .= 'Please feel free to <a href="https://www.2checkout.com/contact/" target="_blank">contact 2Checkout directly</a> for assistance with your integration.';
			
			throw new Exception($message);
		}
		
		$format = $format == null ? Twocheckout::$format : $format;
		switch ($format) {
			case "array":
				$response = self::objectToArray($contents);
				self::checkError($response);
				break;
			case "force_json":
				$response = self::objectToJson($contents);
				break;
			default:
				$response = self::objectToArray($contents);
				self::checkError($response);
				$response = json_encode($contents);
				$response = json_decode($response);
		}
		
		return $response;
	}
	
	public static function objectToArray($object)
	{
		$object = json_decode($object, true);
		$array = [];
		foreach ($object as $member => $data) {
			$array[$member] = $data;
		}
		
		return $array;
	}
	
	public static function objectToJson($object)
	{
		return json_encode($object);
	}
	
	public static function getRecurringLineitems($saleDetail)
	{
		$i = 0;
		$invoiceData = [];
		
		while (isset($saleDetail['sale']['invoices'][$i])) {
			$invoiceData[$i] = $saleDetail['sale']['invoices'][$i];
			$i++;
		}
		
		$invoice = max($invoiceData);
		$i = 0;
		$lineitemData = [];
		
		while (isset($invoice['lineitems'][$i])) {
			if ($invoice['lineitems'][$i]['billing']['recurring_status'] == "active") {
				$lineitemData[] = $invoice['lineitems'][$i]['billing']['lineitem_id'];
			}
			$i++;
		};
		
		return $lineitemData;
	}
	
	public static function checkError($contents)
	{
		if (isset($contents['errors'])) {
			throw new Twocheckout_Error($contents['errors'][0]['message']);
		} else if (isset($contents['exception'])) {
			throw new Twocheckout_Error($contents['exception']['errorMsg'], $contents['exception']['errorCode']);
		}
	}
}
