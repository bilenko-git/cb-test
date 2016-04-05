<?php
namespace MyProject\Tests;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
require_once 'test_restrict.php';
require_once 'common/rates.php';
require_once 'base_rates.php';

/**
 * Test restrictions:
 * - Should be at minimum 1 available room
 *
 */
class booking_availability extends base_rates{
    use \Rates;
    private $bookingUrl = 'http://{server}/reservas/{property_reserva_code}';
    private $bookingSettings = 'http://{server}/connect/{property_id}#/bookingSettings';
   // private $reservationsUrl = 'http://{server}/connect/{property_id}#/newreservations';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99',
        'end' => '+100 days',
        'start' => '+0 days',
        'min' => '0',
        'edit_end_day' => '+12 days'
    );


    public function testSteps() {


        $test = $this;
        //$this->setupInfo('wwwdev.ondeficar.com', 'selenium@test.com', '123qwe', 366);
        $this->setupInfo('PMS_user');

        $this->loginToSite();

        $this->addRate($this->interval);

      //  $interval_id = $this->rates_add_rate($this->interval);


        $this->startDate = date('Y-m-d', strtotime('+10 days'));
        $this->endDate = date('Y-m-d', strtotime('+1 day', strtotime($this->startDate)));

        print_r($this->startDate);
        print_r($this->endDate);

        $url = $this->_prepareUrl($this->bookingUrl).'#checkin='.$this->startDate.'&checkout='.$this->endDate;
        $this->url($url);
        $this->waitForLocation($url);

        try {
            $this->waitForElement('.room_types .room:first', 20000, 'jQ');
        }
        catch (\Exception $e)
        {
            $this->fail('No rooms to booking');
        }


        $count = $this->execute(array('script' => "return window.$('.room_types .rooms_select:first option').length ", 'args' => array()));

        $id = $this->execute(array('script' => "return window.$('.room_types div:first').data('room_type_id');", 'args' => array()));

       print_r("-------------".$count);
        print_r('---------------'.$id);
        $this->url($this->_prepareUrl($this->bookingSettings));
        $this->waitForLocation($this->_prepareUrl($this->bookingSettings));

        $this->waitForElement('#layout [name=booking_max_quantity]', 20000, 'jQ');
        $this->waitForElement('[name=booking_max_quantity]', 20000, 'css');

        $this->execute(array('script' => "window.$('#layout [name=booking_max_quantity]').click(); return false", 'args' => array()));

        $this->execute(array('script' => "window.$('#layout [name=booking_limit_quantity]').click();  return false", 'args' => array()));

        $el  =  $this->execute(array('script' => "return window.$('[data-id=".$id."]:first td:eq(1)').html(); ", 'args' => array()));
        $this->assertEquals($el, $count -1);
        $this->save();


      //  sleep(5);

        $url = $this->_prepareUrl($this->bookingUrl).'#checkin='.$this->startDate.'&checkout='.$this->endDate;
        $this->url($url);
       // $this->waitForLocation($url);

        try {
            $this->waitForElement('.room_types .room:first', 20000, 'jQ');
        }
        catch (\Exception $e)
        {
            $this->fail('No rooms to booking');
        }


        $count = $this->execute(array('script' => "return window.$('.room_types .rooms_select:first option').length ", 'args' => array()));

        $id = $this->execute(array('script' => "return window.$('.room_types div:first').data('room_type_id');", 'args' => array()));

        $this->url($this->_prepareUrl($this->bookingSettings));
        $this->waitForLocation($this->_prepareUrl($this->bookingSettings));

        $this->waitForElement('#layout [name=booking_max_quantity]', 20000, 'jQ');
        $this->waitForElement('[name=booking_max_quantity]', 20000, 'css');

        $this->execute(array('script' => "window.$('#layout [name=booking_max_quantity]').click(); return false", 'args' => array()));

        $this->execute(array('script' => "window.$('#layout [name=booking_limit_quantity]').click();  return false", 'args' => array()));

        $el  =  $this->execute(array('script' => "return window.$('[name=\"quantity_cell_limit[".$id."]\"]').val(); ", 'args' => array()));
        $this->assertEquals($el, $count -1);
        $this->save();
        /*
         *  $el  = $this->waitForElement('[data-id="quantity_cell_limit[914]"]', 20000, 'jQ');
                $booking_max_quantity = $this->waitForElement('#layout [name=booking_max_quantity]', 15000, 'jQ');
                $booking_max_quantity->click();*/


      //  $this->rates_remove_rate();
        $this->delRate();

    }
}