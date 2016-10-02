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
        // $this->prepare_data_booking();
        // $this->loginToSite();
        // $this->link_taxes_on_the_source_page();
        $this->go_to_the_booking_page();
        $this->check_booking_fees();
        // $this->goToSite();
        // $this->remove_data_booking();
    }

    private function prepare_data_booking() {
        $this->create_booking_fees();
        $this->create_booking_room_types();
        $this->create_booking_intervals();
    }

    private function create_booking_fees() {
        foreach ($this->fees as $fee) {
            $this->fees_add($fee);
        }
    }

    private function create_booking_room_types() {
        foreach($this->room_types as $room_type) {
            $this->inventory_create_room_type($room_type, true);
        }
    }

    private function create_booking_intervals() {
        foreach($this->room_types as $room_type) {
            $this->set_default_rates($room_type);
        }
    }

    private function link_taxes_on_the_source_page() {
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

    private function go_to_the_booking_page() {
        $this->startDate = date('Y-m-d', strtotime('next monday'));
        $this->endDate = date('Y-m-d', strtotime('+3 day', strtotime($this->startDate)));
        $url = $this->_prepareUrl($this->bookingUrl).'#checkin='.$this->startDate.'&checkout='.$this->endDate;
        $this->url($url);
        $this->waitForLocation($url);
    }

    private function check_booking_fees() {
        $this->waitForElement(".rooms_select .btn.dropdown-toggle", 15000, 'css');
        foreach($this->room_types as $room_type) {
            $this->execute(array('script' => 'return $(".room_types .room:contains(\''.$room_type['name'].'\') .rooms_select").addClass("open");', 'args' => array()));
            $this->waitForElement(".rooms_select li[data-original-index=3]", 15000, 'jQ')->click();
            // $this->execute(array('script' => 'return $(".room_types .room:contains(\''.$room_type['name'].'\') .rooms_select li[data-original-index=3]").click();', 'args' => array()));
            // $this->waitForElement(".room_types .room:contain('".$room_type['name']."') .rooms_select .btn.dropdown-toggle", 15000, 'css')->click();
            // $this->waitForElement(".room_types .room:contain('".$room_type['name']."') .rooms_select li[data-original-index=3]", 15000, 'jQ')->click();
        }
        $this->waitForElement(".general_info .book_now", 15000, 'css')->click();
        foreach($this->fees as $fee) {
            $fee_amount = $this->execute(array('script' => 'return CBBooking.parseCurrency($(".taxes_and_fees .row:contains(\''.$fee['name'].'\')").find(".sum").text());', 'args' => array()));
            $this->assertEquals($fee_amount, $fee['booking_value']);
        }
    }

    private function goToSite() {
        $url = $this->_prepareUrl($this->siteUrl);
        $this->url($url);
        $this->waitForLocation($url);
        $this->waitForBETLoaded();
    }

    private function remove_data_booking() {
        $this->remove_booking_fees();
        $this->remove_booking_room_types();
    }

    private function remove_booking_fees() {
        foreach ($this->fees as $fee) {
            $this->fees_remove($fee['name']);
        }
    }

    private function remove_booking_room_types() {
        foreach($this->room_types as $room_type) {
            $this->inventory_delete_room_type($room_type);
        }
    }

    private function set_default_rates($room_type) {
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
            'booking_value' => 90
        ),
        'fee_percentage_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Inc',
            'name_changed' => 'Fee Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'booking_value' => 90
        ),
        'fee_fixed_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Exc',
            'name_changed' => 'Fee Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive',
            'booking_value' => 90
        ),
        'fee_fixed_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Inc',
            'name_changed' => 'Fee Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive',
            'booking_value' => 90
        ),
        'fee_fixed_accm' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Accomodations',
            'name_changed' => 'Fee Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive',
            'booking_value' => 90
        ),
        'fee_fixed_res' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Reservations',
            'name_changed' => 'Fee Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive',
            'booking_value' => 90
        ),
        'tax_percentage_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Exc',
            'name_changed' => 'Tax Percentage Exc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'booking_value' => 90
        ),
        'tax_percentage_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Inc',
            'name_changed' => 'Tax Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'booking_value' => 90
        ),
        'tax_fixed_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Exc',
            'name_changed' => 'Tax Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive',
            'booking_value' => 90
        ),
        'tax_fixed_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Inc',
            'name_changed' => 'Tax Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive',
            'booking_value' => 90
        ),
        'tax_fixed_accm' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Accomodations',
            'name_changed' => 'Tax Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive',
            'booking_value' => 90
        ),
        'tax_fixed_res' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Reservations',
            'name_changed' => 'Tax Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive',
            'booking_value' => 90
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
}
?>
