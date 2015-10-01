<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
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
        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');
       // $this->waitForElement('#layout_container>.loading:not(.hide)', 15000, 'jQ');

    }

}
?>
