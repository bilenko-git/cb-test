<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/fees.php';
require_once 'common/rates.php';
require_once 'common/inventory.php';

class room_revenue extends test_restrict {
    use \Fees, \Rates, \Inventory;

    public function test_room_revenue_reservation() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();

        //$this->createRoomType();
        //$this->createBookingIntervals();


        /*temporary*/
        $this->url($this->_prepareUrl($this->fees_and_taxes));
        $this->waitForLocation($this->_prepareUrl($this->fees_and_taxes));
        /*end*/

        //$this->createBookingTaxes_and_Fees();

        $this->linkTaxesOnTheSourcePage();
    }

    public function createRoomType(){
        $rmt = array(
            'name' => 'room type selenium 1',
            'rooms' => 1,
            'room_type_descr_langs' => 'room types used for selenium testing room revenue'
        );

        //$this->roomtype_delRoomType($rmt);

        $room_type_id = $this->roomtype_addRoomType($rmt);
        return array_merge($rmt, array('room_type_id' => $room_type_id));
    }

    private function createBookingIntervals() {
        foreach($this->room_types as $room_type) {
            $this->setDefaultRates($room_type);
        }
    }

    private function setDefaultRates($room_type) {
        //$this->rate_delAllRates($room_type);

        foreach($this->std_intervals as $std_int) {
            $this->rate_addRate($std_int, $room_type);
        }
    }

    private function createBookingTaxes_and_Fees() {
        foreach ($this->taxes_and_fees as $taxes_and_fees) {
            $this->fees_add($taxes_and_fees);
        }
    }

    private function linkTaxesOnTheSourcePage() {
        $this->execute(array('script' => "return BET.navigation.url('sources');", 'args' => array()));
        $this->waitForElement("#layout .sources-table tr:contains('Website') .configure_taxes_fees", 15000, 'jQ')->click();
        $this->waitForElement("#modal_primary_source .ms-parent.source_taxes button.ms-choice", 15000, 'jQ')->click();
        for ($i = 0; $i < 9; $i++) {
            $this->execute(array('script' => "return $($('#modal_primary_source .ms-parent.source_taxes .md-checkbox input')[".$i."]).click();", 'args' => array()));
        }
        $this->byJQ('#modal_primary_source .btn-primary.blue.edit')->click();
        $this->waitForElement("#layout .sources-table", 15000, 'css');
        sleep(5);
    }




















    private $siteUrl = 'http://{server}/connect/{property_id}';
    private $bookingUrl = 'http://{server}/reservas/{property_reserva_code}';
    private $bookingDoneUrl = 'http://{server}/reservation/confirmation';
    private $sourceUrl = 'http://{server}/connect/{property_id}#/sources';

    /*temporary*/
    private $fees_and_taxes = 'http://{server}/connect/{property_id}#/fees_and_taxes';
    /*end*/

    private $room_types = array(
        array(
            'name' => 'room type selenium 1',
            'rooms' => 30,
            'room_type_descr_langs' => 'room type selenium 1'
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

    private $taxes_and_fees = array(
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
}