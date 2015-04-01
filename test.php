<?php
require('exacttarget_soap_client.php');

$wsdl = 'https://webservice.exacttarget.com/etframework.wsdl';
//$email_address='keyur4nonto@gmail.com';
//$first_name='keyur';
//$last_name='patel';
//echo "<pre>";
try{
        /* Create the Soap Client */
        $client = new ExactTargetSoapClient($wsdl, array('trace'=>1));

        /* Set username and password here */
            $client->username = 'jaywilner';
            $client->password = 'clovecay!100';

            /* Create the list object that will be associated with the send */
            $list = new ExactTarget_List();
            
			$list->ID = "3786797";

            /* Create the send definition list object that will be associated with the send */
            /* You could also create a data extension object and use that instead */
            $senddeflist = new ExactTarget_SendDefinitionList();
            $senddeflist->List = $list;
            $senddeflist->DataSourceTypeID = "List"; // in this example, we are sending to a list

            /* Create the email object that is associated with the send */
            $email = new ExactTarget_Email();
            $email->ID = "4498674";

            /* Create the send classification that is associated with this send */
            $sendclass = new ExactTarget_SendClassification();
            $sendclass->CustomerKey = "test";

            /* Create the email send definition object */
            $esd = new ExactTarget_EmailSendDefinition();
            $esd->SendDefinitionList = $senddeflist;
            $esd->Email = $email;
            $esd->Name = "API Created2";
            $esd->SendClassification = $sendclass;
            $object = new SoapVar($esd, SOAP_ENC_OBJECT, 'EmailSendDefinition', "http://exacttarget.com/wsdl/partnerAPI");

            /* Create the email send definition in ExactTarget */
            $request = new ExactTarget_CreateRequest();
            $request->Options = NULL;
            $request->Objects = array($object);
            $results = $client->Create($request);

            /* Output the results */
            //var_dump($results);
            echo '<pre>';
            print_r($results);
            echo '<pre>';

} catch (SoapFault $e) {
//var_dump($e);
echo '==><pre>';
print_r($e);
echo '<pre><==';
}

try{
        /* Create the Soap Client */
        $client = new ExactTargetSoapClient($wsdl, array('trace'=>1));

        /* Set username and password here */
        $client->username = 'jaywilner';
        $client->password = 'clovecay!100';

            // Create the Perform Request and set the action to 'start'
            $pr = new ExactTarget_PerformRequestMsg();
            $pr->Action = "start";   
        
            // Define the customer/external key for the email send definition we want to start
            $esd = new ExactTarget_EmailSendDefinition();
			$esd->CustomerKey = "12345"; // unique identifier for the Email Send Definition

            // Define the definition for the Perform request
            $pr->Definitions =  array();
            $pr->Definitions[] = new SoapVar($esd, SOAP_ENC_OBJECT, 'EmailSendDefinition', "http://exacttarget.com/wsdl/partnerAPI");
            $pr->Options = NULL;

            // Perform/Start the Email Send Definition
            $results = $client->Perform($pr);  

            /*echo '<pre>';
            var_dump($results);
            echo '</pre>';*/
            echo '==><pre>';
			print_r($results);
			echo '<pre><==';

} catch (SoapFault $e) {
    //var_dump($e);
    echo '==><pre>';
	print_r($e);
	echo '<pre><==';
}
?>