<?php
namespace MyProject\Tests;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
require_once 'test_restrict.php';
require_once 'common/rates.php';

/**
 * Test restrictions:
 * - Should be at minimum 1 available room
  * 
 */
class no_collect_deposit extends test_restrict{
    use \Rates;
    private $bookingUrl = 'http://{server}/reservas/{property_id}';
    private $reservationsUrl = 'http://{server}/connect/{property_id}#/newreservations';
    private $policiesUrl = 'http://{server}/connect/{property_id}#/terms';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99',
        'end' => '+140 days',
        'start' => '+0 days',
        'min' => '0',
        'edit_end_day' => '+12 days'
    );

    
    public function testSteps() {
        $test = $this;
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        
        $this->rates_add_rate($this->interval);
        
        //with the cc collecting
        $res1 = $this->_checkState(true);
        
        //without the cc collecting
        $res2 = $this->_checkState(false);
        
        /*remove rates*/
        /*$this->setupInfo('PMS_super_user');
        $this->loginToSite();*/
        $url = $this->_prepareUrl($this->reservationsUrl);
        $this->url($url);
        $this->waitForLocation($url);
        $this->rates_remove_rate();
        
        $this->assertEquals($res1 && $res2, true);
    }
    
    private function _checkState($collectingEnabled) {
        /*Set no deposit*/
        $this->_setNoDepositPolicy($collectingEnabled);
        
        $this->startDate = date('Y-m-d', strtotime('next monday'));
        $this->endDate = date('Y-m-d', strtotime('+1 day', strtotime($this->startDate)));

        $url = $this->_prepareUrl($this->bookingUrl).'#checkin='.$this->startDate.'&checkout='.$this->endDate;
        $this->url($url);
        $this->waitForLocation($url);

        //looking for first room block in list
        try {
            $this->waitForElement('.room_types .room:first', 20000, 'jQ');
        }
        catch (\Exception $e)
        {
            $this->fail('No rooms to booking');
        }

        $this->byjQ('.room_types .room:first div.rooms_select button')->click();
        $this->byjQ('.room_types .room:first div.rooms_select div.dropdown-menu ul li:eq(1) a')->click();
        
        $this->byCssSelector('button.book_now')->click();
        
        try {
            $this->waitForElement('button.finalize', 20000);
        }
        catch (\Exception $e)
        {
            $this->fail('Waiting for booking response timeout');
        }
        
        //check the deposit
        $el = $this->byjQ('p.total_deposit');
        $deposit = $s = floatval(str_replace(',', '.', $el->text()));
        
        $payment = $this->byjQ('div.payment_method');
        
        $res = $deposit == 0 && ($collectingEnabled && $payment || !$collectingEnabled && !$payment);
        
        //TO DO
        //NEED to add adding the reservation
        //If collecting enabled need to check if CC data present in the reservation after adding new one
        
        return $res;
    }
    
    private function _setNoDepositPolicy($state) {
        $url = $this->_prepareUrl($this->policiesUrl);
        $this->url($url);
        $this->waitForLocation($url);
        
        /*Just to enable save button if the state the same as we need*/
        $element = $this->waitForElement('#layout input[name=\'terms_deposit_type\'][value=\'percent\'] + label', 15000, 'jQ');
        $element->click();
        /**/
        
        $element = $this->waitForElement('#layout input[name=\'terms_deposit_type\'][value=\'no_deposit\'] + label', 15000, 'jQ');
        $element->click();
        $this->execute(array('script' => '$(\'#layout input[name="no_deposit_cc_collect"]\').bootstrapSwitch(\'state\', '.($state?'true':'false').')', 'args' => array()));
        $this->save();
    }
}