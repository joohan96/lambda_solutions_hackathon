<?php 
	define("USERNAME", "george.mihailov@lambdasolutions.net");
	define("PASSWORD", "lambdasecret1");
	define("SECURITY_TOKEN", "zGzLgLtwcoViBkDe5r7gAYQMn");

	require_once ('soapclient/SforceEnterpriseClient.php');
	echo "Success";
try {	
	$mySforceConnection = new SforceEnterpriseClient();
	$mySforceConnection->createConnection('soapclient/enterprise.wsdl.xml');
	$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
	echo "Success";

	// Simple example of session management - first call will do
    // login, refresh will use session ID and location cached in
    // PHP session
    if (isset($_SESSION['enterpriseSessionId'])) {
        $location = $_SESSION['enterpriseLocation'];
        $sessionId = $_SESSION['enterpriseSessionId'];

        $mySforceConnection->setEndpoint($location);
        $mySforceConnection->setSessionHeader($sessionId);

        echo "Used session ID for enterprise<br/><br/>\n";
  	} else {
        $mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
        $_SESSION['enterpriseLocation'] = $mySforceConnection->getLocation();
        $_SESSION['enterpriseSessionId'] = $mySforceConnection->getSessionId();

        echo "Logged in with enterprise<br/><br/>\n";
    }
    $query = "SELECT OwnerId, Website, BillingStreet, Phone from Account";
    $response = $mySforceConnection->query($query);

    class Account {
    	public $account_owner;
	    public $website;
	    public $billing_address;
	    public $phone;
	}

    echo "Results of query '$query'<br/><br/>\n";
    $data = array();
    foreach ($response->records as $record) {
    	$counter = 0; 
        //echo $record->OwnerId.": ".$record->Website." ".$record->BillingStreet." ".$record->Phone."<br/>\n";
        $account = new Account(); 
        $account->account_owner = $record->OwnerId;
        $account->website = $record->Website;
        $account->billing_address = $record->BillingStreet;
        $account->phone = $record->Phone;

        echo $account->account_owner.": ".$account->website." ".$account->billing_address." ".$account->phone."<br/>\n";

        $data[] = $account;
    }

}
catch (Exception $e) {
  echo $mySforceConnection->getLastRequest();
  echo $e->faultstring;
}
?>