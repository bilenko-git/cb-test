<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/fees.php';
require_once 'common/rates.php';
require_once 'common/inventory.php';

class fees_and_taxes extends test_restrict {
    use \Fees, \Rates, \Inventory;

    public function test_booking_page_calculations() {
        $this->setupInfo('PMS_user');
        // $this->loginToSite();
        // $this->prepareDataBooking();
        // $this->loginToSite();
        // $this->linkTaxesOnTheSourcePage();
        $this->goToTheBookingPage();
        $this->checkBookingFees();
        $this->makeBookingReservation();
        // $this->goToSite();
        // $this->removeDataBooking();
    }

    private function prepareDataBooking() {
        $this->createBookingFees();
        $this->createBookingRoomTypes();
        $this->createBookingIntervals();
    }

    private function createBookingFees() {
        foreach ($this->fees as $fee) {
            $this->fees_add($fee);
        }
    }

    private function createBookingRoomTypes() {
        foreach($this->room_types as $room_type) {
            $this->inventory_create_room_type($room_type, true);
        }
    }

    private function createBookingIntervals() {
        foreach($this->room_types as $room_type) {
            $this->setDefaultRates($room_type);
        }
    }

    private function linkTaxesOnTheSourcePage() {
        $this->execute(array('script' => "return BET.navigation.url('sources');", 'args' => array()));
        $this->waitForElement("#layout .sources-table tr:contains('Website') .configure_taxes_fees", 15000, 'jQ')->click();
        $this->waitForElement("#modal_primary_source .ms-parent.source_taxes button.ms-choice", 15000, 'jQ')->click();
        for ($i = 0; $i < 12; $i++) {
            $this->execute(array('script' => "return $($('#modal_primary_source .ms-parent.source_taxes .md-checkbox input')[".$i."]).click();", 'args' => array()));
        }
        $this->byJQ('#modal_primary_source .btn-primary.edit')->click();
        $this->waitForElement("#layout .sources-table", 15000, 'css');
        sleep(5);
    }

    private function goToTheBookingPage() {
        $this->startDate = date('Y-m-d', strtotime('next monday'));
        $this->endDate = date('Y-m-d', strtotime('+10 day', strtotime($this->startDate)));
        $url = $this->_prepareUrl($this->bookingUrl).'#checkin='.$this->startDate.'&checkout='.$this->endDate;
        $this->url($url);
        $this->waitForLocation($url);
    }

    private function checkBookingFees() {
        $this->waitForElement(".rooms_select .btn.dropdown-toggle", 15000, 'css');
        foreach($this->room_types as $room_type) {
            $this->execute(array('script' => 'return $(".room_types .room:contains(\''.$room_type['name'].'\') .rooms_select").addClass("open");', 'args' => array()));
            $this->waitForElement(".rooms_select li[data-original-index=3]", 15000, 'jQ')->click();
        }
        $this->waitForElement(".general_info .book_now", 15000, 'css')->click();
        foreach($this->fees as $fee) {
            $fee_amount = $this->execute(array('script' => 'return CBBooking.parseCurrency($(".taxes_and_fees .row:contains(\''.$fee['name'].'\')").find(".sum").text());', 'args' => array()));
            $this->assertEquals($fee['expecting_booking_value'], $fee_amount);
        }
    }

    private function makeBookingReservation() {
        $context = '#reservationDetailsForm ';
        $this->fillForm(array(
            '#first_name' => $this->reservation['first_name'],
            '#last_name' => $this->reservation['last_name'],
            '#email' => $this->reservation['email'],
            '#phone' => $this->reservation['phone'],
            '#country' => $this->reservation['country'],
            // '.payment_method ' => [$this->reservation['payment'], false, true],
            // '#agree_terms' => ['checked', false, true]
        ), $context);
        $this->execJS('$("#ebanking").click();');
        $this->execJS('$("#agree_terms").click();');
        $this->execJS('$(".finalize").click();');
        sleep(5);
    }

    private function goToSite() {
        $url = $this->_prepareUrl($this->siteUrl);
        $this->url($url);
        $this->waitForLocation($url);
        $this->waitForBETLoaded();
    }

    private function removeDataBooking() {
        $this->removeBookingFees();
        $this->removeBookingRoomTypes();
    }

    private function removeBookingFees() {
        foreach ($this->fees as $fee) {
            $this->fees_remove($fee['name']);
        }
    }

    private function removeBookingRoomTypes() {
        foreach($this->room_types as $room_type) {
            $this->inventory_delete_room_type($room_type);
        }
    }

    private function setDefaultRates($room_type) {
        $this->rate_delAllRates($room_type);

        foreach($this->std_intervals as $std_int) {
            $this->rate_addRate($std_int, $room_type);
        }
    }

    private $siteUrl = 'http://{server}/connect/{property_id}';
    private $bookingUrl = 'http://{server}/reservas/{property_reserva_code}';
    private $sourceUrl = 'http://{server}/connect/{property_id}#/sources';

    private $fees = array(
        'fee_percentage_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Exc',
            'name_changed' => 'Fee Percentage Exc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00'
        ),
        'fee_percentage_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Inc',
            'name_changed' => 'Fee Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '400.00'
        ),
        'fee_fixed_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Exc',
            'name_changed' => 'Fee Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00'
        ),
        'fee_fixed_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Inc',
            'name_changed' => 'Fee Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '600.00'
        ),
        'fee_fixed_accm' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Accomodations',
            'name_changed' => 'Fee Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '60.00'
        ),
        'fee_fixed_res' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Reservations',
            'name_changed' => 'Fee Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '10.00'
        ),
        'tax_percentage_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Exc',
            'name_changed' => 'Tax Percentage Exc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00'
        ),
        'tax_percentage_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Inc',
            'name_changed' => 'Tax Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '400.00'
        ),
        'tax_fixed_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Exc',
            'name_changed' => 'Tax Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00'
        ),
        'tax_fixed_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Inc',
            'name_changed' => 'Tax Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '600.00'
        ),
        'tax_fixed_accm' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Accomodations',
            'name_changed' => 'Tax Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '60.00'
        ),
        'tax_fixed_res' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Reservations',
            'name_changed' => 'Tax Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '10.00'
        )
    );

    private $room_types = array(
        array(
            'name' => 'Room Type Fees And Taxes Check 1',
            'rooms' => 10,
            'room_type_descr_langs' => 'Room Type Fees And Taxes Check Description 1'
        ) , array(
            'name' => 'Room Type Fees And Taxes Check 2',
            'rooms' => 10,
            'room_type_descr_langs' => 'Room Type Fees And Taxes Check Description 2'
        )
    );

    private $std_intervals = array(
        'i1' => array(
            'name' => 'rate 1',
            'start' => '+0 day',
            'end' => '+30 day',
            'value_today' => 100
        )
    );

    private $reservation = array(
        'first_name' => 'SE First Name',
        'last_name' => 'SE Last Name',
        'email' => 'selenium@test.test',
        'phone' => '+1234567890',
        'country' => 'UA',
        'payment' => 'ebanking'
    );
}
?>
