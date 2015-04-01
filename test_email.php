<?php
require('exacttarget_soap_client.php');
echo "<pre>";

$wsdl = 'https://webservice.s4.exacttarget.com/etframework.wsdl';
try{
 /* Create the Soap Client */
        $client = new ExactTargetSoapClient($wsdl, array('trace'=>1));
        $client->username = 'jaywilner';
        $client->password = 'clovecay!100';
       
        /*% ExactTarget_Subscriber */    $subscriber = new ExactTarget_Subscriber();
                $subscriber->SubscriberKey = "dishant.potenza@gmail.com";
                $subscriber->EmailAddress = "dishant.potenza@gmail.com";
               
                /*% ExactTarget_Subscriber */                  
                $attribute1 = new ExactTarget_Attribute();
                $attribute1->Name = "HTML__Body";
                $attribute1->Value = "<html><body><div align='center'><table border='1' cellspacing='0' cellpadding=0 width='95%' style='width:95.0%'><tr><td>Welcome, You are sending Email using TriggeredSend definition</td></tr></table></div><br>Testing</body></html>";
            
              
                $subscriber->Attributes[] = $attribute1;               
               
                $ts = new ExactTarget_TriggeredSend(); 
            
                $tsd = new ExactTarget_TriggeredSendDefinition(); 
                $tsd->CustomerKey = "Shipping_Confirmation_Key";               
               
                $ts->Subscribers = $subscriber;
                $ts->TriggeredSendDefinition = $tsd;

                $object = new SoapVar($ts, SOAP_ENC_OBJECT, 'TriggeredSend', "http://exacttarget.com/wsdl/partnerAPI");

                //print_r($object);
                 echo "<br><br>";
                  $request = new ExactTarget_CreateRequest();
    $request->Options = NULL;
    $request->Objects = array($object);

    $results = $client->Create($request);

    print_r($results);
     } catch (SoapFault $e) {
    /* output the resulting SoapFault upon an error */
    print_r($e);
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



<?php 
require('exacttarget_appexchange_soap_client.php');

$wsdl = 'https://etappx.exacttarget.com/etframeworksf.wsdl';

try{
        /* Create the Soap Client */
        $client = new ExactTargetSoapClient($wsdl, array('trace'=>1));

        /* Set username and password here */
       // $client->username = 'xxx';
        //$client->password = 'xxx';
		 $client->username = 'jaywilner';
        $client->password = 'clovecay!100';
        //Setup the Email Send Definition
             $SFSend = new ExactTarget_SalesforceSend();
             $email = new ExactTarget_Email();
             $email->ID = '3099626';
             $SFSend->Email = $email;
             $SFSend->FromAddress = 'help@exacttarget.com';
             $SFSend->FromName = 'MAC'; 
             $contact = new ExactTarget_Target();
             $contact->ObjectID = '003A000000A7kKBIAZ';
             $contact->ObjectType = 'Contact';

             $SFSend->Targets = array(new SoapVar($contact, SOAP_ENC_OBJECT, 'Target', "http://exacttarget.com/wsdl/partnerAPI"));

             $object = new SoapVar($SFSend, SOAP_ENC_OBJECT, 'SalesforceSend', "http://exacttarget.com/wsdl/partnerAPI");

             $request = new ExactTarget_CreateRequest();
             $request->Options = NULL;
             $request->Objects = array($object);

             $results = $client->Create($request);

             var_dump($results);

} catch (Exception $e) {
echo 'Message: ' .$e->getMessage();
}
?>