<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class add_interval_date_error extends test_restrict{
        private $roomrate_url = 'http://{server}/connect/{property_id}#/roomRates';

    public function testSteps(){
        $this->setupInfo('PMS_user');
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        // waitForEval
        // clickElement
        $this->url($this->_prepareUrl($this->roomrate_url));
        // waitForElementAttribute
        // clickElement
        $this->waitForElement('.add_interval', 15000, 'css')->click();
        $this->waitForElement('[name=interval_name]', 15000, 'css')->value('error');
        $this->byClassName('save_add_interval')->click();
        $this->waitForElement("#error_modal", 15000, 'css');
        $this->waitForElement("#error_modal .default", 15000, 'css')->click();
  }
}
