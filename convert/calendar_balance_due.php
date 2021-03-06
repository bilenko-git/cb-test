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
class calendar_balance_due extends test_restrict{
    use \Rates;
    private $bookingUrl = 'http://{server}/reservas/{property_reserva_code}';
    private $calendarUrl = 'http://{server}/connect/{property_id}#/calendar';
    private $reservationsUrl = 'http://{server}/connect/{property_id}#/newreservations';
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
        //need SU privileges to remove reservas
        //$this->setupInfo('wwwdev.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 366);
        $this->setupInfo('PMS_super_user');

        $this->loginToSite();
        $interval_id = $this->rates_add_rate($this->interval);
        
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
            $el = $this->waitForElement('.reserve_number', 50000);
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
        $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-number:contains(\''.$this->reservationNumber.'\')', 20000, 'jQ');
       
        if(!$el)
            $this->fail('Cannot find the reservation');
        
        $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-guest a', 20000, 'jQ');
        
        $reservationId = $this->getAttribute($el, 'data-id');
        $el->click();

        $this->betLoaderWaiting();

        $this->waitForElement('#layout #reservation-summary #rs-totals-container .rs-show-res-totals', 20000, 'jQ')->click();
        $el = $this->waitForElement('#layout #reservation-summary .rs-totals-table tr:last td:last', 20000, 'jQ');

        $balanceDue = preg_replace('/^([0-9.,]+).*$/', "$1", $el->text());

        $this->waitForElement('[href=\'#rs-folio-tab\']', 5000, 'jQ', true)->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.add-payment-btn', 5000, 'jQ', true)->click();

        /*        $this->byJQ('#layout .payments_block:eq(0) .booking-payments-add-form tr .ch_input');*/

        $element = $this->waitForElement("#add-new-payment-modal [name='payment_type'] option:last", 15000, 'jQ');
        $element->selected();
        $element->click();
        
        //loading waiting

        $element = $this->waitForElement("#add-new-payment-modal select.payment_inputs.payment_base option:eq(0)", 15000, 'jQ');
        $element->selected();
        $element->click();

        $this->waitForElement("#add-new-payment-modal [name='paid']", 15000, 'jQ')->clear();
        $this->waitForElement("#add-new-payment-modal [name='paid']", 15000, 'jQ')->value($balanceDue);
        $this->waitForElement('#add-new-payment-modal .add-new-payment-usual', 15000, 'jQ')->click();

        $this->betLoaderWaiting();

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
        $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-number:contains(\''.$this->reservationNumber.'\')', 20000, 'jQ');
       
        if(!$el)
            $this->fail('Cannot find the reservation');
        
        $el = $this->waitForElement('#layout .reservations-table tbody tr:eq(0) td.res-guest a', 20000, 'jQ');
        
        $reservationId = $this->getAttribute($el, 'data-id');
        $el->click();
        
        $this->betLoaderWaiting();

        try {
            $el = $this->waitForElement('#layout .delete-button-reservation', 20000, 'jQ');
        }
        catch (\Exception $e)
        {
            $this->fail('Cannot get serch element');
        }

        $el->click();
        $this->waitForElement('.btn_delete')->click();

        //waiting for redirect to reservas page
        try {
            $el = $this->waitForElement('#layout input[name="find_reservations"]', 20000);
        }
        catch (\Exception $e)
        {
            $this->fail('Deleting so long...');
        }

        $this->betLoaderWaiting();

        $url = $this->_prepareUrl($this->reservationsUrl);
        $this->url($url);
        $this->waitForLocation($url);
        $this->rates_remove_rate();

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

            sleep(2);
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
                $this->fail('Waiting for non assigned rooms timeout');
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
            //need to waite up to 10 secs toll calendar updated from server
            sleep(11);
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