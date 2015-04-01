<?php
require('exacttarget_soap_client.php');

$wsdl = 'https://webservice.exacttarget.com/etframework.wsdl';
echo "<pre>";
try{
    /* Create the Soap Client */
    $client = new ExactTargetSoapClient($wsdl, array('trace'=>1));

    /* Set username and password
    * 
    *  here */
 $client->username = 'jaywilner';
        $client->password = 'clovecay!100';

    $ts = new ExactTarget_TriggeredSend();
    $tsd = new ExactTarget_TriggeredSendDefinition();
    $tsd->CustomerKey = "thankyou";

    $sub = new ExactTarget_Subscriber();    $sub->EmailAddress = "dishant.potenza@gmail.com";
    $sub->SubscriberKey = "dishant.potenza@gmail.com";

$LeadType =  new ExactTarget_Attribute(); $LeadType->Name = "Lead_Type";  $LeadType->Value = "auto";
$LeadID =  new ExactTarget_Attribute();  $LeadID->Name = "Lead_ID";  $LeadID->Value = "999965";
$EmailHash =  new ExactTarget_Attribute();  $EmailHash->Name = "Email_Hash";  $EmailHash->Value = "059bfef71d8c83c384f845390191df39fba8941cbcb934abbe5e15a74f25bab3";
$ConsumerFirstName =  new ExactTarget_Attribute();  $ConsumerFirstName->Name = "Consumer_First_Name";  $ConsumerFirstName->Value = "Angel";
$ConsumerLastName =  new ExactTarget_Attribute();  $ConsumerLastName->Name = "Consumer_Last_Name";  $ConsumerLastName->Value = "Ruiz";

    $sub->Attributes = array($LeadType,$LeadID,$EmailHash,$ConsumerFirstName,$ConsumerLastName);
    $ts->Subscribers = array();
    $ts->Subscribers = $sub;    $ts->TriggeredSendDefinition = $tsd;

    $object = new SoapVar($ts, SOAP_ENC_OBJECT, 'TriggeredSend', "http://exacttarget.com/wsdl/partnerAPI");

    //var_dump($object);
    echo "<br><br>";
    $request = new ExactTarget_CreateRequest();
    $request->Options = NULL;
    $request->Objects = array($object);

    $results = $client->Create($request);

    //var_dump($results);

} catch (SoapFault $e) {
    var_dump($e);
}


print "Request 1: \n".   $client->__getLastRequestHeaders() ."\n";
print "Request 2: \n".
$client->__getLastRequest() ."\n";
print "Response 1: \n".
$client->__getLastResponseHeaders()."\n";
print "Response 2: \n".
$client->__getLastResponse()."\n";

echo "</pre>";
                 
?>