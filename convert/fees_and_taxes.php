<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/fees.php';
require_once 'common/rates.php';
require_once 'common/inventory.php';

class fees_and_taxes extends test_restrict {
    use \Fees, \Rates, \Inventory;

    private $fees = array(
        'percentage' => array(
            'name' => 'Fee Percentage',
            'name_changed' => 'Fee Percentage Changed',
            'amount_type' => 'percentage',
            'amount' => '10'
        ),
        'fixed' => array(
            'name' => 'Fee Fixed',
            'name_changed' => 'Fee Fixed Changed',
            'amount_type' => 'fixed',
            'amount' => '10'
        ),
        'fixed_per_accomodation' => array(
            'name' => 'Fee Per Accomodations',
            'name_changed' => 'Fee Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10'
        ),
        'fixed_per_reservation' => array(
            'name' => 'Fee Per Reservations',
            'name_changed' => 'Fee Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10'
        )
    );

    private $taxes = array(
        'percentage' => array(
            'name' => 'Tax Percentage',
            'name_changed' => 'Tax Percentage Changed',
            'amount_type' => 'percentage',
            'amount' => '10'
        ),
        'fixed' => array(
            'name' => 'Tax Fixed',
            'name_changed' => 'Tax Fixed Changed',
            'amount_type' => 'fixed',
            'amount' => '10'
        ),
        'fixed_per_accomodation' => array(
            'name' => 'Tax Per Accomodations',
            'name_changed' => 'Tax Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10'
        ),
        'fixed_per_reservation' => array(
            'name' => 'Tax Per Reservations',
            'name_changed' => 'Tax Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10'
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
        $this->fees_add($this->fees['percentage'], 'fee');
        $this->fees_add($this->taxes['percentage'], 'tax');
        $this->add_reservation();
        $this->add_transactions();
    }

    private function clear_data() {
        $this->fees_remove($this->fees['percentage']['name_changed']);
        $this->fees_remove($this->taxes['percentage']['name_changed']);
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

    /* SECTION OF TESTS */

    public function test_rename_and_change_transactions_descriptions() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->prepare_data();
        $this->fees_change_name($this->fees['percentage']['name'], $this->fees['percentage']['name_changed']);
        $this->check_transactions();
        $this->fees_change_name($this->taxes['percentage']['name'], $this->taxes['percentage']['name_changed']);
        $this->check_transactions();
        $this->add_adjustments();
        $this->check_transactions();
        $this->clear_data();
    }
//
//    public function test_fee_percentage_per_night() {
//        $this->setupInfo('PMS_user');
//        $this->loginToSite();
//        $this->inventory_create_room_type($this->room_type, true);
//        $this->set_default_rates($this->room_type);
//        $this->fees_add_fee($this->fees['percentage']);
//        $this->fees_remove_fee($this->fees['percentage']['name']);
//        $this->inventory_delete_room_type($this->room_type);
//    }
//
//    public function test_tax_percentage_per_night() {
//        $this->setupInfo('PMS_user');
//        $this->loginToSite();
//        $this->inventory_create_room_type($this->room_type, true);
//        $this->set_default_rates($this->room_type);
//        $this->taxes_add_tax($this->taxes['percentage']);
//        $this->taxes_remove_tax($this->taxes['percentage']['name']);
//        $this->inventory_delete_room_type($this->room_type);
//    }

}
?>
