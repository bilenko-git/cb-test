<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class invoice extends test_restrict{
    private $invoice_url = 'http://{server}/connect/{property_id}#/setupInvoicing';

    public function testSteps(){
      $br =  array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Windows 8.1',
            ));
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->invoice_url));
        $this->waitForLocation($this->_prepareUrl($this->invoice_url));

        $this->waitForElement("[name='title']", 15000, 'css')->value("Innnnnnnvoice");
        $this->save();
       // $this->waitForElement('#layout_container>.loading:not(.hide)', 15000, 'jQ');

    }

}
?>
