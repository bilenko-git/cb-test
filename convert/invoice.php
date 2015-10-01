<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
use PHPUnit_Extensions_SeleniumTestCase_Driver as Driver;
use phpunit_extensions_selenium2testcase_keysholder as ka;

class invoice extends test_restrict{
    private $invoice_url = 'http://{server}/connect/{property_id}#/setupInvoicing';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';


    public function testSteps(){
      $br =  array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Windows 8.1',
            ));
        $this->setupInfo('', '', '', 366, $br);
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
