<?php
// Include the helper gateway class
require_once(app_path(),  'functions\AfricasTalkingGateway.php');
$username   = "LOGIC";
$apikey     = "d55cc2f1b6d649bb44ed1a39f8ab2623305a5ebafb442938bebc9a5f3e21e764";
$gateway  = new AfricaStalkingGateway($username, $apikey);
try 
{
  $lastReceivedId = 0;
  do {
    
    $results = $gateway->fetchMessages($lastReceivedId);
    foreach($results as $result) {
      echo " From: " .$result->from;
      echo " To: " .$result->to;
      echo " Message: ".$result->text;
      echo " Date Sent: " .$result->date;
      echo " LinkId: " .$result->linkId;
      echo " id: ".$result->id;
      echo "\n";
      $lastReceivedId = $result->id;
      
    }
  } while ( count($results) > 0 );
}
catch ( AfricasTalkingGatewayException $e )
{
  echo "Encountered an error: ".$e->getMessage();
}


