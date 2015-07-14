<?php
namespace MyProject\Tests;
require_once 'availability_base_test.php';

class add_rate extends availability_base_test{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99'
    );
    public function testSteps(){
        $step = $this;

        $this->setupInfo('', '', '', 366);
        $this->loginToSite();
        $this->addRate($this->interval);
    }
    public function addRate($interval){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#tab_0', 15000, 'css')->click();
        $add_new_rate_plan = $this->waitForElement('#tab_0 .add_interval', 15000, 'css');
        $add_new_rate_plan->click();
        $this->byName('interval_name')->value($interval['name']);
        $this->byName('start_date')->click();
        $this->byCssSelector('.ui-datepicker-today')->click();

        $this->byJQ(".define_week_days td:not(._hide) input")
            ->clear()
            ->value($interval['value_today']);

        //$this->byCssSelector('')

    }
}
?>
