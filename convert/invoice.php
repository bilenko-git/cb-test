<?php
namespace MyProject\Tests;
require_once 'base_rates.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class invoice extends test_restrict{
    private $invoice_url = 'http://{server}/connect/{property_id}#/setupInvoicing';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';

    public function testSteps(){
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->invoice_url));
        $this->waitForLocation($this->_prepareUrl($this->invoice_url));
        $this->waitForElement('.invoice_preview', 15000, 'css')->click();
        $this->waitForElement('#layout_container>.loading:not(.hide)', 15000, 'jQ');
       // $this->keys(Keys::ENTER);
        $this->keys(Keys::ESCAPE);
        $this->keys(Keys::ESCAPE);
        $this->keys(Keys::ESCAPE);
        $this->keys(Keys::ESCAPE);

        $this->keysSpecial('escape');$this->keysSpecial('escape');$this->keysSpecial('escape');
        $this->waitForElement('#layout_container>.loading.hide', 15000, 'css');
      //  $this->keyDown('theTextbox', '98');
        $this->keysSpecial('escape');
    }

}
?>
