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
        
        $bookPageCheck = $deposit == 0 && ($collectingEnabled && $payment || !$collectingEnabled && !$payment);
        
        $reservaCheck = $this->_checkReservation($collectingEnabled);
        
        //TO DO
        //NEED to add adding the reservation
        //If collecting enabled need to check if CC data present in the reservation after adding new one
        
        return $bookPageCheck && $reservaCheck;
    }
    
    private function _checkReservation($collectingEnabled) {
        $this->byCssSelector('select[name="country"]')->value('AF');
        $this->byId('first_name')->value('fn');
        $this->byId('last_name')->value('ln');
        $this->byId('email')->value('fn@ln.com');
        
        
        if($collectingEnabled) {
            $this->byCssSelector('.payment_method label[for="card"]')->click();

            try {
                $this->waitForElement('#cardholder_name', 20000);
            }
            catch (\Exception $e)
            {
                $this->fail('Card selecting timeout');
            }

            $this->byId('cardholder_name')->value('check ok');
            $this->byId('card_number')->value('4111111111111111');
            $this->byId('exp_month')->value(3);
            $this->byId('exp_year')->value(date('Y')+1);
            $this->byId('cvv')->value('123');

            $this->execute(array('script' => 'window.$("#agree_terms").click()', 'args' => array()));
            $this->byCssSelector('button.finalize')->click();

            try {
                $el = $this->waitForElement('.reserve_number', 50000);
            }
            catch (\Exception $e)
            {
                $this->fail('Waiting for reservation status timeout');
            }

            $this->reservationNumber = $el->text();

            //add payment to make balance due = 0 and check
            $url = $this->_prepareUrl($this->reservationsUrl);
            $this->url($url);
            $this->waitForLocation($url);

            try {
                $el = $this->waitForElement('#layout input[name="find_reservations"]', 20000);
            }
            catch (\Exception $e)
            {
                $this->fail('Cannot get serch element');
            }

            $el->click();
            $this->keys($this->reservationNumber.Keys::ENTER);
            $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-number:contains(\''.$this->reservationNumber.'\')', 20000, 'jQ');

            if(!$el)
                $this->fail('Cannot find the reservation');

            $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-guest a', 20000, 'jQ');

            $reservationId = $this->getAttribute($el, 'data-id');
            $el->click();

            //loading waiting
            $this->waitUntil(function() use ($test) {
                try {
                    $test->assertEquals("0", $test->execute(array('script' => "return window.$('#layout .loading.locked').length", 'args' => array())));
                } catch(\Exception $e) {
                    return null;
                }
                return true;
            },50000);

            try {
                $el = $this->waitForElement('#layout #reservation-summary td.remaining_amount', 20000);
            }
            catch (\Exception $e)
            {
                $this->fail('Cannot get balance due');
            }

            $balanceDue = floatval(str_replace(',', '.', $el->text()));

            if($collectingEnabled) {
                //now check if cc data was collected
                $this->byJQ('#layout #reservation-summary .btn-view-credit-cards')->click();
                
                //loading waiting
                $this->waitUntil(function() use ($test) {
                    try {
                        $test->assertEquals("0", $test->execute(array('script' => "return window.$('#layout .loading').length", 'args' => array())));
                    } catch(\Exception $e) {
                        return null;
                    }
                    return true;
                },50000);
                
                if($this->byJQ('ul[id="#layout "]')) {
                    
                }
                
                
                

                try {
                $el = $this->waitForElement('#layout #reservation-summary .booking-payments-add-form select[name=\'payment_type\']', 20000);
                }
                catch (\Exception $e)
                {
                    $this->fail('Cannot get serch element');
                }

                $el->value('check');

                $this->byJQ('#layout #reservation-summary .booking-payments-add-form input[name=\'paid\']')->value($balanceDue);

                try {
                    $el = $this->waitForElement('#layout #reservation-summary .booking-payments-add-form .btn-save-payment', 20000);
                }
                catch (\Exception $e)
                {
                    $this->fail('Cannot get search element');
                }

                $el->click();

                //loading waiting
                $this->waitUntil(function() use ($test) {
                    try {
                        $test->assertEquals("0", $test->execute(array('script' => "return window.$('#layout .loading.locked').length", 'args' => array())));
                    } catch(\Exception $e) {
                        return null;
                    }
                    return true;
                },50000);
            }
        }
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