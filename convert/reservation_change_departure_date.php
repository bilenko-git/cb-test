<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

class reservation_change_departure_date extends test_restrict{
    private $reservations_url = 'http://{server}/connect/{property_id}#/reservations';
    private $reservation_url = 'http://{server}/connect/{property_id}#/reservations/';
    private $bookingUrl = 'http://{server}/reservas/{property_reserva_code}';
    private $day = 2;
    private $interval = array(
        'end' => '+5 days',
        'start' => '+0 days',
    );

    public function testSteps(){
        $this->setupInfo('PMS_user');

        $this->startDate = date('Y-m-d', strtotime('+0 days'));
        $this->endDate = date('Y-m-d', strtotime('+4 day', strtotime($this->startDate)));
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

        $this->waitForElement('.payment_method label[for=card]', 15000, 'jQ')->click();

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
        $this->url($this->_prepareUrl($this->reservations_url));
        $this->waitForLocation($this->_prepareUrl($this->reservations_url));

        $this->waitForElement('.list_reservation_table', 15000, 'css');
        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.data_booked_wid.res-booking', 15000, 'css')->click();
        $this->betLoaderWaiting();

        $id = $this->execute(array('script' => 'return window.$("#layout .reservations-table tbody tr:first .view_summary").data("id");', 'args' => array()));
        $this->url($this->_prepareUrl($this->reservation_url.$id));
        $this->waitForElement('#rs-accomodations-tab', 15000, 'css');

        $el = $this->waitForElement('.booking-room-row td:eq(6)', 15000, 'jQ');
        $old_night = $el->text();
        $this->waitForElement('#rs-accomodations-tab .edit_btn', 15000, 'css')->click();
        $this->waitForElement('#rs-accomodations-tab .edit-room-dates-btn', 15000, 'css')->click();

        $this->waitForElement('#edit-room-dates-modal', 15000, 'css');

        //$l = $this->execute(array('script' => "return window.$('#edit-room-price-modal .day-rate-cell.date-by-interval:not(.disabled) input:visible').length", 'args' => array()));
        for($i=0;$i < $this->day;$i++){
            print_r("+++++++++++++++++");
            print_r($i);
            print_r("+++++++++++++++++");
            $this->execute(array('script' => 'window.$("#edit-room-dates-modal .day-prices td:not(.selected):eq(0) input").click(); return false;', 'args' => array()));
        }

        $this->waitForElement('#edit-room-dates-modal .update-room-dates', 15000, 'css')->click();
        $this->betLoaderWaiting();

        $this->waitForElement('#rs-accomodations-tab .edit_btn', 15000, 'css')->click();
        $this->waitForElement('#rs-accomodations-tab .edit-room-dates-btn', 15000, 'css')->click();

        $this->waitForElement('#edit-room-dates-modal', 15000, 'css');


        //$l = $this->execute(array('script' => "return window.$('#edit-room-price-modal .day-rate-cell.date-by-interval:not(.disabled) input:visible').length", 'args' => array()));
        for($i=0;$i < $this->day;$i++){
            print_r("+++++++++++++++++");
            print_r($i);
            print_r("+++++++++++++++++");
            $this->execute(array('script' => 'window.$("#edit-room-dates-modal .day-prices td.selected:eq(0) input").click(); return false;', 'args' => array()));
        }

        $this->waitForElement('#edit-room-dates-modal .update-room-dates', 15000, 'css')->click();
        $this->betLoaderWaiting();

        $el = $this->waitForElement('.booking-room-row td:eq(6)', 15000, 'jQ');
        $new_night= $el->text();
        $this->assertEquals($old_night, $new_night);

        $this->waitForElement('.delete-button-reservation', 15000, 'css')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
        $this->waitForLocation($this->_prepareUrl($this->reservations_url));

    }
}
?>
