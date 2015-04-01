<?php
// This is a sample PHP script that demonstrates accepting a POST from the
// Unbounce form submission webhook, and then sending an email notification.
/*function stripslashes_deep($value) {
$value = is_array($value) ?
array_map('stripslashes_deep', $value) :
stripslashes($value);
return $value;
}
// First, grab the form data. Some things to note:
// 1. PHP replaces the '.' in 'data.json' with an underscore.
// 2. Your fields names will appear in the JSON data in all lower-case,
// with underscores for spaces.
// 3. We need to handle the case where PHP's 'magic_quotes_gpc' option
// is enabled and automatically escapes quotation marks.
if (get_magic_quotes_gpc()) {
$unescaped_post_data = stripslashes_deep($_POST);
} else {
$unescaped_post_data = $_POST;
}
$form_data = json_decode($unescaped_post_data['data_json']);
// If your form data has an 'Email Address' field, here's how you extract it:
$email_address = $form_data->email[0];
$first_name = $form_data->first_name[0];
$last_name = $form_data->last_name[0];

// Grab the remaining page data...
$page_id = $_POST['page_id'];
$page_url = $_POST['page_url'];
$variant = $_POST['variant'];
// Assemble the body of the email...


$message_body = 
<<<EOM
Email: $email_address \n
first_name: $first_name \n
last_name: $last_name \n
Page ID: $page_id \n
URL: $page_url \n
Variant: $variant \n
EOM;
//$message_body=json_encode($_POST);
mail('keyur4monto@gmail.com',
'New Unbounce Form Submission!',
$message_body);

$myfile = fopen("result.txt", "w") or die("Unable to open file!");
$dt =date('Y-m-d h:i:s')."\n";
fwrite($myfile, $dt);
fclose($myfile);*/
echo "<pre>";

$email_address='mayur4monto@gmail.com';
$first_name='keyur';
$last_name='patel';

require('exacttarget_soap_client.php');

$wsdl = 'https://webservice.exacttarget.com/etframework.wsdl';

try {
        /* Create the Soap Client */
        $client = new ExactTargetSoapClient($wsdl, array('trace'=>1));

        /* Set username and password here */
        $client->username = 'jaywilner';
        $client->password = 'clovecay!100';

        $subscriber = new ExactTarget_Subscriber();
        $subscriber->EmailAddress = $email_address;
        $subscriber->SubscriberKey = $email_address;

        // This section is needed if you are adding a subscriber to a Lock and Publish account using an enterprise API user
        //$cl = new ExactTarget_ClientID();
        //$cl->ID = 123;
        //$subscriber->Client = $cl;
        $subscriber->Lists = array();   
    
        $ExampleAttribute1 = new ExactTarget_Attribute();
        $ExampleAttribute1->Name = "First Name";
        $ExampleAttribute1->Value = $first_name;   

        $ExampleAttribute2 = new ExactTarget_Attribute();
        $ExampleAttribute2->Name = "Last Name";
        $ExampleAttribute2->Value = $last_name;

        $subscriber->Attributes=array($ExampleAttribute1,$ExampleAttribute2);      

        $list = new ExactTarget_SubscriberList();
        $list->ID = "3786797"; // This is the ID of the subscriber list             
        $subscriber->Lists[] = $list;

        // This is the section needed to define it as an "Upsert"
        $so = new ExactTarget_SaveOption();
        $so->PropertyName = "*";
        $so->SaveAction = ExactTarget_SaveAction::UpdateAdd;            
        $soe = new SoapVar($so, SOAP_ENC_OBJECT, 'SaveOption', "http://exacttarget.com/wsdl/partnerAPI");            
        $opts = new ExactTarget_UpdateOptions();            
        $opts->SaveOptions = array($soe);
            
        // Below are examples of updating the subscriber status to active or unsub
        //$subscriber->Status = ExactTarget_SubscriberStatus::Active;
        //$subscriber->Status = ExactTarget_SubscriberStatus::Unsubscribed;

        $object = new SoapVar($subscriber, SOAP_ENC_OBJECT, 'Subscriber', "http://exacttarget.com/wsdl/partnerAPI");
            
        $request = new ExactTarget_CreateRequest();
        $request->Options = $opts;
        $request->Objects = array($object);            
		//echo "<pre>";
		//print_r($object);
		//echo "<pre>";
            
       // $results = $client->Create($request);
		//echo "<pre>";
		//print_r($results);
		//echo "<pre>";
		$key="PHP_Test_".uniqid();
		///email sending from here
		//Setup the Email Send Definition
		$emailSendDef = new ExactTarget_EmailSendDefinition();
		$emailSendDef->CustomerKey =$key;
		$emailSendDef->Name = "Definition ".$key;

		//Setup the Send Classification
		$sendClass = new ExactTarget_SendClassification();
		$sendClass->CustomerKey = "Default Commercial";
		$emailSendDef->SendClassification = $sendClass;

		// Setting Up the Source List
		$emailSendDef->SendDefinitionList = array();
		$sendDefList = new ExactTarget_SendDefinitionList();
		
		$list = new ExactTarget_List();
		$list->ID = "3786797";
		$sendDefList->List = $list;
		$sendDefList->DataSourceTypeID = "List";
		$sendDefList->SendDefinitionListType = "SourceList";
		$emailSendDef->SendDefinitionList[] = $sendDefList;
		
		// Setting up the exclude list
		//$sendDefListExclude = new ExactTarget_SendDefinitionList();
		//$listExclude = new ExactTarget_List();
		//$listExclude->ID = "3786797";
		//$sendDefListExclude->List = $listExclude;$sendDefListExclude->DataSourceTypeID = "List";
		//$sendDefListExclude->SendDefinitionListType = "ExclusionList";$emailSendDef->SendDefinitionList[] = $sendDefListExclude;
		//$sendDefListExclude->SendDefinitionListType = "ExclusionList";$emailSendDef->SendDefinitionList[] = $sendDefListExclude;

		// Specify the Email To Send
		$email = new ExactTarget_Email();
		$email->ID = "4498674";
		$emailSendDef->Email = $email;
		
		
		$object = new SoapVar($emailSendDef, SOAP_ENC_OBJECT, 'EmailSendDefinition', "http://exacttarget.com/wsdl/partnerAPI");
		$request = new ExactTarget_CreateRequest();
		$request->Options = NULL;
		$request->Objects = array($object);		

		$results = $client->Create($request);
		
		
		print "EmailSendDefinition Response \n".
		$client->__getLastResponse()."\n";
		
		print "EmailSendDefinition Request \n".
		$client->__getLastRequest()."\n\r\n\r\n";
		
		
		$tsd = new ExactTarget_TriggeredSendDefinition();
		$tsd->CustomerKey = $key; // unique identifier for the triggered send definition
		$tsd->Name = $tsd->CustomerKey; // set the name to be the same as the customer key
				
		// Define the email to be sent
		$tsd->Email = new ExactTarget_Email(); // create email object to attach to the send
		$tsd->Email->ID = 4498674; // id of the email you want to attach to the triggered send
		
		// Define the send classification that is associated with this send
		$sc = new ExactTarget_SendClassification();
		$sc->CustomerKey = "Default Commercial"; // external key for the send classification we want to use
		$tsd->SendClassification = $sc; // create send classification object to attach to the send
		
		// Send the email as multipart mime
		$tsd->IsMultipart = true; // Send as Multipart MIME
				
		// Create the object
		$object = new SoapVar($tsd, SOAP_ENC_OBJECT, 'TriggeredSendDefinition', "http://exacttarget.com/wsdl/partnerAPI");
				
		// Create the triggered send definition
		$request = new ExactTarget_CreateRequest();
		$request->Options = NULL;
		$request->Objects = array($object);
		$results = $client->Create($request);
		
		print "TriggeredSendDefinition Response \n".
		$client->__getLastResponse()."\n";
		
		print "TriggeredSendDefinition Request \n".
		$client->__getLastRequest()."\n\r\n\r\n";		
		
		
				$ts = new ExactTarget_TriggeredSend(); 
				//$ts->CustomerKey = $key; 
                $tsd = new ExactTarget_TriggeredSendDefinition(); 
                $tsd->CustomerKey = $key; 
				$tsd->TriggeredSendStatus = ExactTarget_TriggeredSendStatusEnum::Active  ;
				$object = new SoapVar($tsd, SOAP_ENC_OBJECT, 'TriggeredSendDefinition', "http://exacttarget.com/wsdl/partnerAPI");
				 
				$request = new ExactTarget_UpdateRequest();
                $UpdateOption = new ExactTarget_UpdateOptions();
                      // Apply options and object to request
				$request->Options = new SoapVar($UpdateOption, SOAP_ENC_OBJECT, 'UpdateOptions', "http://exacttarget.com/wsdl/partnerAPI");
				$request->Objects = array($object);
                               
                                // Execute the CreateRequest
				$results = $client->Update($request);
				 
                $ts->Subscribers = $subscriber;
                $ts->TriggeredSendDefinition = $tsd;
                
                $object = new SoapVar($ts, SOAP_ENC_OBJECT, 'TriggeredSend', "http://exacttarget.com/wsdl/partnerAPI");

                //var_dump($object);
                
			    $request = new ExactTarget_CreateRequest();
			    $request->Options = NULL;
			    $request->Objects = array($object);
				
				$results = $client->Create($request);
				
		
}
catch (SoapFault $e) {
    /* output the resulting SoapFault upon an error */
    var_dump($e);
}




print "TriggeredSend Request: \n".
$client->__getLastRequest() ."\n";

print "TriggeredSend Response: \n".
$client->__getLastResponse()."\n\r\n\r\n";
?>