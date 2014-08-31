<?php
/*
D I S C L A I M E R                                                                                          
WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK.                                                                                   
mozealous.com provides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.   
This code was written by Dave Mozealous (www.mozealous.com) 
*/
$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';

function addUserToGroup ($accountURL, $emailaddress, $password, $customerID, $userID, $groupID) {

$params = "<AddUserToGroupRequest xmlns='http://www.articulate-online.com/services/api/1.0/'>
      <Credentials>
        <EmailAddress>$emailaddress</EmailAddress>
        <Password>$password</Password>
        <CustomerID>$customerID</CustomerID>
      </Credentials>
	  <UserID>$userID</UserID>
      <GroupID>$groupID</GroupID>
    </AddUserToGroupRequest>";

$client = new soapclient("$accountURL/services/api/1.0/ArticulateOnline.asmx?wsdl",array('encoding'=>'UTF-8'));
  ini_set("soap.wsdl_cache_enabled", "0"); 
 
$err = $client->getError();
echo $err;

$client->setUseCurl($useCURL);
$client->loadWSDL();

$userUpdated = $client->call('AddUserToGroup',$params);

//find out if it worked
$userUpdatedStatus = $userUpdated['Success'];

}
?>