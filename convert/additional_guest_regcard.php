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
class additional_guest_regcard extends test_restrict{
    use \Rates;
    private $bookingUrl = 'http://{server}/reservas/{property_id}';
    private $calendarUrl = 'http://{server}/connect/{property_id}#/calendar';
    private $reservationsUrl = 'http://{server}/connect/{property_id}#/newreservations';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99',
        'end' => '+10 days',
        'start' => '+0 days',
        'min' => '0',
        'edit_end_day' => '+12 days'
    );

    
    public function testSteps() {


        $test = $this;
        //$this->setupInfo('wwwdev.ondeficar.com', 'selenium@test.com', '123qwe', 366);
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

        $this->byjQ('.room_types .room:first div.adults_select button')->click();
        $this->byjQ('.room_types .room:first div.adults_select div.dropdown-menu ul li:eq(1) a')->click();
        
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
        
        //additional guest
        $this->byjQ('.add_guest')->click();
        $this->waitForElement('select[name="add_country"]', 20000, 'css', true);
        $this->byCssSelector('select[name="add_country"]')->value('AF');
        $this->byId('add_first_name')->value('check');
        $this->byId('add_last_name')->value('ok');
        $this->byId('add_email')->value('fn@ln.com');
        $this->byjQ('.add_guest_btn')->click();
        
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

        //$this->loginToSite();
        
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
        
        $el = $this->waitForElement('#layout .additional-guests-container .add_guest_link_reg_card', 20000, 'jQ', true);
        
        $el->click();

        //$this->timeouts()->implicitWait(5000);
        
        $flag = strpos($this->source(), 'check ok') !== FALSE ? 1:0;
        
        //need SU privileges to remove reservas
        //$this->setupInfo('wwwdev.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 366);
        $this->setupInfo('PMS_super_user');

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
        if(!$el)
            $this->fail('Cannot find the reservation to delete');
        
        $reservationId = $this->getAttribute($el, 'data-id');
        $el->click();
        
       
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

        $this->rates_remove_rate();
        
        $this->assertEquals($flag, 1);
    }
}