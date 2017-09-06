<?php
require_once('AfricasTalkingGateway.php');
$username   = "LOGIC";
$apikey     = "d55cc2f1b6d649bb44ed1a39f8ab2623305a5ebafb442938bebc9a5f3e21e764";
$recipients = "+254711XXXYYY,+254733YYYZZZ";
$message    = "Please this this code to verify you number". $code;
$gateway    = new AfricasTalkingGateway($username, $apikey);
try 
{ 
  $results = $gateway->sendMessage($recipients, $message);
			
  foreach($results as $result) {
    echo " Number: " .$result->number;
    echo " Status: " .$result->status;
    echo " MessageId: " .$result->messageId;
    echo " Cost: "   .$result->cost."\n";
  }
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error while sending: ".$e->getMessage();
}

