<?php
require __DIR__ . '/vendor/autoload.php';
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

$request= file_get_contents('php://input');
file_put_contents("debug",$request);


$decoded_request = json_decode($request, true);

$msg_type="";
$customer="";
$ournumber="";

if($decoded_request["to"]["type"]=="whatsapp"){
	$msg_type="whatsapp";
	$customer = $decoded_request["from"]["number"];
	$ournumber = $decoded_request["to"]["number"];
	$app_info = getAppIdForWABA($ournumber);
	if($app_info[0] == "None"){
		echo "No Appid";
		exit;
	}

	$jwt = generate_jwt($app_info[0],$app_info[2]);

	sendReply($jwt,$app_info[1],$ournumber,$customer);
}

function getAppIdForWABA($waba){

	$config_file = "config/".$waba.".json";
	$key_file = "keys/".$waba.".key";

	if(file_exists($config_file) == false){
		echo "no config ".$config_file;
		return array("None","None","None");
	}
	if(file_exists($key_file) == false){
		echo "no key";
		return array("None","None","None");
	}
	$config = file_get_contents($config_file);
	$json = json_decode($config, true);
	$appid = $json["appid"];
	$message = $json["message"];
	
	return array($appid,$message,$key_file);	
}
function generate_jwt( $application_id, $keyfile) {

    $jwt = false;
    date_default_timezone_set('UTC');    //Set the time for UTC + 0
    $key = file_get_contents($keyfile);  //Retrieve your private key
    $signer = new Sha256();
    $privateKey = new Key($key);

    $jwt = (new Builder())->setIssuedAt(time() - date('Z')) // Time token was generated in UTC+0
        ->set('application_id', $application_id) // ID for the application you are working with
        ->setId( base64_encode( mt_rand (  )), true)
        ->sign($signer,  $privateKey) // Create a signature using your private key
        ->getToken(); // Retrieves the JWT

    return $jwt;
}

function sendReply($jwt,$message,$waba,$customer){

	$data='{
    		"from": { "type": "whatsapp", "number": "'.$waba.'" },
    		"to": { "type": "whatsapp", "number": "'.$customer.'" },
    		"message": {
      			"content": {
        			"type": "text",
        			"text": '.json_encode($message).'
      			}
    		}
  	}'; 
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json',
	    'Accept: application/json',
	    'Authorization: Bearer '.$jwt
	));
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($curl, CURLOPT_URL, "https://api.nexmo.com/v0.1/messages");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec($curl);
	curl_close($curl);

	echo $result;
}
?>
