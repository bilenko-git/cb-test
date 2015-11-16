<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class house_account extends test_restrict{
    private $house_accounts_url = 'http://{server}/connect/{property_id}#/house_accounts';

    public function testSteps(){
        $this->setupInfo('PMS_user');
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->house_accounts_url));
        $this->waitForLocation($this->_prepareUrl($this->house_accounts_url));
        $this->betLoaderWaiting();
        $this->waitForElement('.add-house-account-btn', 15000, 'jQ')->click();
        $this->waitForElement('#house_account_name', 15000, 'jQ')->value('selenium test '.rand(1000));
        $this->waitForElement('.save_house_account', 15000, 'jQ')->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.view_details:visible:last', 15000, 'jQ')->click();
        $this->waitForElement('#layout .add-payment-btn', 15000, 'css');
    }

}
?>
