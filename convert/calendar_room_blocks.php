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
class calendar_room_blocks extends base_rates{
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

    private function check_ma($rooms){

        $test = $this;
        $this->loginToMA();
        $el = $this->waitForElement('.menu-item-availability a', 15000, 'css');
        $el->click();
        $this->waitForElement('.availTableWrapper', 15000, 'css');
        $rate = $this->waitForElement('/html/body/div[2]/div/div/div/div[8]/div[3]/table/tbody/tr[1]/td[2]/input[1]', 15000, 'xpath');
        $test->assertEquals($rate->value(), $rooms);

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
    }


    public function testSteps() {

        $test = $this;
        $this->setupInfo('PMS_user');
        $this->loginToSite();

        $this->addRate($this->interval);
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


        // add blocked dates
        $this->waitForElement('.room_blocks > a', 15000, 'css')->click();
        $this->waitForElement('.room_blocks .new-block-dates-group', 15000, 'css')->click();
        $this->waitForElement('#block-dates-group-modal', 15000, 'css');

        $this->startDate = date('Y-m-d', strtotime('+0 days'));
        $this->endDate = date('Y-m-d', strtotime('+1 day', strtotime($this->startDate)));

        $el = $this->waitForElement('#block-dates-group-modal [name=start_date]', 15000, 'css');
        $el->clear();
        $el->value($this->startDate);
        $el = $this->waitForElement('#block-dates-group-modal [name=end_date]', 15000, 'css');
        $el->clear();
        $el->value($this->endDate);

        $this->waitForElement('#block-dates-group-modal .select_acc:visible', 15000, 'jQ')->click();
        $this->waitForElement('#block-dates-group-modal li:not(.disabled) a.opt:first', 15000, 'jQ')->click();
        $el = $this->waitForElement('#block-dates-group-modal #block-dates-reason', 15000, 'css');
        $el->clear();
        $el->value($this->endDate);
        $this->waitForElement('#block-dates-group-modal #save-blocked-dates-group', 15000, 'css')->click();
        $this->betLoaderWaiting();

        $el =  $this->waitForElement('.rt_'.$this->startDate.' .free-rooms:first', 15000, 'jQ');
        $rooms = $el->text();

        $el = $this->waitForElement('.calendar-table .content:contains('.$this->endDate.')', 15000, 'jQ');
        $text = $el->text();
        $this->assertEquals($this->endDate, $text);

        $this->check_ma($rooms);

        $el = $this->waitForElement('.calendar-table .content:contains('.$this->endDate.')', 15000, 'jQ');
        $el->click();
        $this->waitForElement('.popover.blocked_dates', 15000, 'jQ');
        $this->waitForElement('.popover.blocked_dates .actions-list li:first a', 15000, 'jQ')->click();

        // add Courtesy Hold

        $this->waitForElement('.room_blocks > a', 15000, 'css')->click();
        $this->waitForElement('.room_blocks .new-courtesy-hold-group', 15000, 'css')->click();
        $this->waitForElement('#courtesy-hold-group-modal', 15000, 'css');

        $this->startDate = date('Y-m-d', strtotime('+0 days'));
        $this->endDate = date('Y-m-d', strtotime('+1 day', strtotime($this->startDate)));

        $el = $this->waitForElement('#courtesy-hold-group-modal [name=start_date]', 15000, 'css');
        $el->clear();
        $el->value($this->startDate);
        $el = $this->waitForElement('#courtesy-hold-group-modal [name=end_date]', 15000, 'css');
        $el->clear();
        $el->value($this->endDate);

        $this->waitForElement('#courtesy-hold-group-modal .select_acc:visible', 15000, 'jQ')->click();
        $this->waitForElement('#courtesy-hold-group-modal li:not(.disabled) a.opt:first', 15000, 'jQ')->click();
        $el = $this->waitForElement('#courtesy-hold-group-modal #courtesy-hold-first-name', 15000, 'css');
        $el->clear();
        $el->value($this->startDate);
        $el = $this->waitForElement('#courtesy-hold-group-modal #courtesy-hold-last-name', 15000, 'css');
        $el->clear();
        $el->value($this->endDate);
        $el = $this->waitForElement('#courtesy-hold-group-modal #courtesy-hold-hrs', 15000, 'css');
        $el->clear();
        $el->value('2');
        $el = $this->waitForElement('#courtesy-hold-group-modal #courtesy-hold-email', 15000, 'css');
        $el->clear();
        $el->value('fil.jlh@bk.ru');
        $el = $this->waitForElement('#courtesy-hold-group-modal #courtesy-hold-phone', 15000, 'css');
        $el->clear();
        $el->value('2');
        $this->waitForElement('#courtesy-hold-group-modal #save-courtesy-hold-group', 15000, 'css')->click();
        $this->betLoaderWaiting();


        $el =  $this->waitForElement('.rt_'.$this->startDate.' .free-rooms:first', 15000, 'jQ');
        $rooms = $el->text();

        $el = $this->waitForElement('.calendar-table .content:contains('.$this->endDate.')', 15000, 'jQ');
        $text = $el->text();
        $this->assertEquals($this->startDate.' '.$this->endDate, $text);

        $this->check_ma($rooms);

        $el = $this->waitForElement('.calendar-table .content:contains('.$this->endDate.')', 15000, 'jQ');
        $el->click();
        $this->waitForElement('.popover.courtesy_hold', 15000, 'jQ');
        $this->waitForElement('.popover.courtesy_hold .actions-list li:eq(1) a', 15000, 'jQ')->click();

        // add Out of Service

        $this->waitForElement('.room_blocks > a', 15000, 'css')->click();
        $this->waitForElement('.room_blocks .new-out-of-service-group', 15000, 'css')->click();
        $this->waitForElement('#block-dates-group-modal', 15000, 'css');

        $this->startDate = date('Y-m-d', strtotime('+0 days'));
        $this->endDate = date('Y-m-d', strtotime('+1 day', strtotime($this->startDate)));

        $el = $this->waitForElement('#block-dates-group-modal [name=start_date]', 15000, 'css');
        $el->clear();
        $el->value($this->startDate);
        $el = $this->waitForElement('#block-dates-group-modal [name=end_date]', 15000, 'css');
        $el->clear();
        $el->value($this->endDate);

        $this->waitForElement('#block-dates-group-modal .select_acc:visible', 15000, 'jQ')->click();
        $this->waitForElement('#block-dates-group-modal li:not(.disabled) a.opt:first', 15000, 'jQ')->click();
        $el = $this->waitForElement('#block-dates-group-modal #block-dates-reason', 15000, 'css');
        $el->clear();
        $el->value($this->endDate);
        $this->waitForElement('#block-dates-group-modal #save-blocked-dates-group', 15000, 'css')->click();
        $this->betLoaderWaiting();

        $el =  $this->waitForElement('.rt_'.$this->startDate.' .free-rooms:first', 15000, 'jQ');
        $rooms = $el->text();

        $el = $this->waitForElement('.calendar-table .content:contains('.$this->endDate.')', 15000, 'jQ');
        $text = $el->text();
        $this->assertEquals($this->endDate, $text);

        $this->check_ma($rooms);

        $el = $this->waitForElement('.calendar-table .content:contains('.$this->endDate.')', 15000, 'jQ');
        $el->click();

        $this->waitForElement('.popover.blocked_dates', 15000, 'jQ');
        $this->waitForElement('.popover.blocked_dates .actions-list li:first a', 15000, 'jQ')->click();

        //////////
        $this->delRate();

    }
}