<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/fees.php';
require_once 'common/rates.php';
require_once 'common/inventory.php';

class fees_and_taxes extends test_restrict {
    use \Fees, \Rates, \Inventory;

    private $bookingUrl = 'http://{server}/reservas/{property_reserva_code}';
    private $sourceUrl = 'http://{server}/connect/{property_id}#/sources';

    private $fees = array(
        'fee_percentage_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Exc',
            'name_changed' => 'Fee Percentage Exc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive'
        ),
        'fee_percentage_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Inc',
            'name_changed' => 'Fee Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive'
        ),
        'fee_fixed_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Exc',
            'name_changed' => 'Fee Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive'
        ),
        'fee_fixed_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Inc',
            'name_changed' => 'Fee Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive'
        ),
        'fee_fixed_accm' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Accomodations',
            'name_changed' => 'Fee Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive'
        ),
        'fee_fixed_res' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Reservations',
            'name_changed' => 'Fee Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive'
        ),
        'tax_percentage_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Exc',
            'name_changed' => 'Tax Percentage Exc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive'
        ),
        'tax_percentage_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Inc',
            'name_changed' => 'Tax Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive'
        ),
        'tax_fixed_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Exc',
            'name_changed' => 'Tax Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive'
        ),
        'tax_fixed_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Inc',
            'name_changed' => 'Tax Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive'
        ),
        'tax_fixed_accm' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Accomodations',
            'name_changed' => 'Tax Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive'
        ),
        'tax_fixed_res' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Reservations',
            'name_changed' => 'Tax Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive'
        )
    );

    private $room_type = array(
        'name' => 'Room Type Fees And Taxes Check',
        'rooms' => 10,
        'room_type_descr_langs' => 'Room Type Fees And Taxes Check Description'
    );

    private $std_intervals = array(
        'i1' => array(
            'name' => 'rate 1',
            'start' => '+1 day',
            'end' => '+7 day',
            'min' => 1,
            'max' => 5,
            'value_today' => 2
        )
    );

    private function prepare_data() {
        $this->fees_add($this->fees['fee_percentage_exl']);
        $this->fees_add($this->fees['tax_percentage_exl']);
        $this->add_reservation();
        $this->add_transactions();
    }

    private function clear_data() {
        $this->fees_remove($this->fees['fee_percentage_exl']['name_changed']);
        $this->fees_remove($this->fees['tax_percentage_exl']['name_changed']);
        $this->remove_reservation();
        $this->remove_transactions();
    }

    private function add_reservation() {
        return false;
    }

    private function add_transactions() {
        return false;
    }

    private function check_transactions() {
        return false;
    }

    private function add_adjustments() {
        return false;
    }

    private function remove_reservation() {
        return false;
    }

    private function remove_transactions() {
        return false;
    }

    private function set_default_rates($room_type) {
        $this->rate_delAllRates($room_type);

        foreach($this->std_intervals as $std_int) {
            $this->rate_addRate($std_int, $room_type);
        }
    }

    private function prepare_data_booking() {
        foreach ($this->fees as $fee) {
            $this->fees_add($fee);
        }
        sleep(3);
    }

    private function link_taxes_on_the_source_page() {
        $this->execute(array('script' => "return BET.navigation.url('sources');", 'args' => array()));
        $this->waitForElement("#layout .sources-table tr:contains('Website') .configure_taxes_fees", 15000, 'jQ')->click();
        sleep(3);
        $this->execute(array('script' => 'return $("#apply_tax_to_primary option").prop("checked", true);', 'args' => array()));
        $this->byJQ('#modal_primary_source .btn-primary.edit')->click();
        $this->waitForElement("#layout .sources-table", 15000, 'css');
        return false;
    }

    private function go_to_the_booking_page() {
        return false;
    }

    private function check_booking_fees() {
        return false;
    }

    private function remove_data_booking() {
        foreach ($this->fees as $fee) {
            $this->fees_remove($fee['name']);
        }
    }

    /* SECTION OF TESTS */

    // public function test_rename_and_change_transactions_descriptions() {
    //     $this->setupInfo('PMS_user');
    //     $this->loginToSite();
    //     $this->prepare_data();
    //     $this->fees_change_name($this->fees['fee_percentage_exl']['name'], $this->fees['fee_percentage_exl']['name_changed']);
    //     $this->check_transactions();
    //     $this->fees_change_name($this->taxes['tax_percentage_exl']['name'], $this->taxes['tax_percentage_exl']['name_changed']);
    //     $this->check_transactions();
    //     $this->add_adjustments();
    //     $this->check_transactions();
    //     $this->clear_data();
    // }

    public function test_booking_page_calculations() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->prepare_data_booking();
        $this->link_taxes_on_the_source_page();
        $this->go_to_the_booking_page();
        $this->check_booking_fees();
        $this->remove_data_booking();
    }

    // public function test_fee_percentage_per_night() {
    //     $this->setupInfo('PMS_user');
    //     $this->loginToSite();
    //     $this->inventory_create_room_type($this->room_type, true);
    //     $this->set_default_rates($this->room_type);
    //     $this->fees_add($this->fees['percentage'], 'fee');
    //     $this->fees_remove($this->fees['percentage']['name']);
    //     $this->inventory_delete_room_type($this->room_type);
    // }
    //
    // public function test_tax_percentage_per_night() {
    //     $this->setupInfo('PMS_user');
    //     $this->loginToSite();
    //     $this->inventory_create_room_type($this->room_type, true);
    //     $this->set_default_rates($this->room_type);
    //     $this->fees_add($this->taxes['percentage'], 'tax');
    //     $this->fees_remove($this->taxes['percentage']['name']);
    //     $this->inventory_delete_room_type($this->room_type);
    // }

}
?>
