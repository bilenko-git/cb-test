<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class fAdd_min_max_rate extends test_restrict{
    private $invoice_url = 'http://{server}/connect/{property_id}#/setupInvoicing';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';

    public function testSteps(){
        $this->loginToSite();
        $this->url($this->_prepareUrl($this->invoice_url));
        $this->waitForLocation($this->_prepareUrl($this->invoice_url));
        $this->waitForElement('.invoice_preview', 15000, 'css')->click();
        $this->waitForElement('.loading', 15000, 'css');
        $this->waitForElement('.loading.hide', 15000, 'css');
    }

}
?>
