<?php
namespace MyProject\Tests;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
require_once 'test_restrict.php';

/**
 * Test restrictions:
 * - Should be at minimum 1 available room
  * 
 */
class calendar_balance_due extends test_restrict{
    private $bookingUrl = 'http://{server}/reservas/{property_id}';
    private $calendarUrl = 'http://{server}/connect/{property_id}#/calendar';
    private $reservationsUrl = 'http://{server}/connect/{property_id}#/newreservations';
    
    public function testSteps() {
        $test = $this;
        //need SU privileges to remove reservas
        $this->setupInfo('wwwdev.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 366);
        
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
        
        $this->byCssSelector('select[name="country"]')->value('AF');
        $this->byId('first_name')->value('fn');
        $this->byId('last_name')->value('ln');
        $this->byId('email')->value('fn@ln.com');
        $this->byCssSelector('.payment_method label[for="card"]')->click();
        
        try {
            $this->waitForElement('#cardholder_name', 20000);
        }
        catch (\Exception $e)
        {
            $this->fail('Card selecting timeout');
        }
        
        $this->byId('cardholder_name')->value('fn ln');
        $this->byId('card_number')->value('4111111111111111');
        $this->byId('exp_month')->value(3);
        $this->byId('exp_year')->value(date('Y')+1);
        $this->byId('cvv')->value('123');

        $this->execute(array('script' => 'window.$("#agree_terms").click()', 'args' => array()));
        $this->byCssSelector('button.finalize')->click();
        
        try {
            $el = $this->waitForElement('.reserve_number', 20000);
        }
        catch (\Exception $e)
        {
            $this->fail('Waiting for reservation status timeout');
        }
        
        $this->reservationNumber = $el->text();

        $this->loginToSite();
        $this->_checkCalendar(1, true);
        
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
        //$this->byCssSelector("#layout .list_reservation_table")->click();
        //$this->waitForElement('.loading:not(.hide)', 15000, 'jQ');
        sleep(3);
        $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-guest a', 20000, 'jQ');
       // $el = $this->byjQ('#layout .reservations-table tbody tr:eq(0) td.res-guest a');
        if(!$el)
            $this->fail('Cannot find the reservation');
        
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
        
        $balanceDue = preg_replace('/^([0-9.,]+).*$/', "$1", $el->text());
        
        $this->byJQ('#layout #reservation-summary .btn-add-payment')->click();
        
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

        sleep(3);
        
        //check calendar again
        $this->_checkCalendar(0, false);
        
        //delete reservation
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
        sleep(3);
        $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-guest a', 20000, 'jQ');
        if(!$el)
            $this->fail('Cannot find the reservation to delete');
        
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
            $el = $this->waitForElement('#layout a.delete-button-reservation', 20000);
        }
        catch (\Exception $e)
        {
            $this->fail('Cannot get serch element');
        }
        
        $el->click();
        $this->waitForElement('#confirm_delete');
        $this->waitForElement('.btn_delete')->click();

        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('#layout .loading.locked').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);
    }
    
    private function _checkCalendar($checkFlag = 1, $assign = false)
    {
        $test = $this;
        //going to calendar page
        $url = $this->_prepareUrl($this->calendarUrl);
        $this->url($url);
        $this->waitForLocation($url);
        
        //loading waiting
        $this->waitUntil(function() use ($test) {
            try {
                $test->assertEquals("0", $test->execute(array('script' => "return window.$('#layout .loading.locked').length", 'args' => array())));
            } catch(\Exception $e) {
                return null;
            }
            return true;
        },50000);

        if($assign)
        {
            try {
                $el = $this->waitForElement('#layout #assignments_btn');
            }
            catch (\Exception $e)
            {
                $this->fail('Waiting for Assignments timeout');
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

            //setDate
            $this->execJS("
            $('#layout #assignments-datepicker').datepicker('setDate', '".date('m/d/Y', strtotime($this->startDate))."');
            $('#layout #assignments-datepicker .ui-datepicker-current-day').click();
            ");

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
                $this->waitForElement('#layout .assignments-wrapper .need-to-assign:eq(0)', 20000, 'jQ');
            }
            catch (\Exception $e)
            {
                $this->fail('Waiting for non sssigned rooms timeout');
            }

            //looking for our booking
            $el = $this->execJS('
                var $selTestEls = $(\'#layout .assignments-wrapper .need-to-assign\');
                var $el = null,
                    data = \'\';

                $selTestEls.each(function(){
                    data = JSON.parse($(this).attr(\'data-event\'));
                    if(data.identifier == ' . $this->reservationNumber . ')
                    {
                        $el = $(this);
                        return false;
                    }
                });
                return $el?$el[0]:null;
            ');

            if($el)
                $el = $this->elementFromResponseValue($el);
            else
                $this->fail('Cannot find the reservation');

            $el->click();

            try {
                $el = $this->waitForElement('#layout button.assign-room', 20000);
            }
            catch (\Exception $e)
            {
                $this->fail('Waiting assigment finished timeout');
            }

            $el->click();
        }
        else
        {
            //change date
            $this->execJS("
            $('#layout #calendar-datepicker').datepicker('setDate', '".date('m/d/Y', strtotime($this->startDate))."');
            $('#layout #calendar-datepicker .ui-datepicker-current-day').click();
            ");

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
        
        try {
            $this->waitForElement('#layout div[data-booking_id=\''.$this->reservationNumber.'\']', 20000, 'jQ');
        }
        catch (\Exception $e)
        {
            $this->fail('Cannot get assigned element from calendar');
        }
 
        $flag = $this->execJS('return $(\'#layout div[data-booking_id="'.$this->reservationNumber.'"] .balance_due\').length');
        
        $this->assertEquals($flag, $checkFlag);
     }
}