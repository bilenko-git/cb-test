<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';
require_once 'common/fees.php';
require_once 'common/rates.php';
require_once 'common/inventory.php';

class room_revenue extends test_restrict {
    use \Fees, \Rates, \Inventory;
    private $house_accounts_url = 'http://{server}/connect/{property_id}#/house_accounts';

    public function test_room_revenue() {
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->preparation();

        $this->RoomRevenue_with_default_taxes_and_fees();
        $this->RoomRevenue_with_customs_taxes_and_fees();
        $this->RoomRevenue_without_taxes_and_fees();

        $this->add_house_account();
        $this->HouseAccount_test_room_revenue_with_TX();
        $this->HouseAccount_test_room_revenue_without_TX();

        $this->all_remove();
    }

    private function HouseAccount_test_room_revenue_with_TX() {
        $this->house_add_room_revenue_with_TX();
        $this->house_check_room_revenue($this->room_revenue['expecting_value'], $this->room_revenue['name']);
        $this->house_check_room_revenue_with_taxes_and_fees();
        $this->house_remove_room_revenue();
    }

    private function HouseAccount_test_room_revenue_without_TX() {
        $this->house_add_room_revenue_without_TX();
        $this->house_check_room_revenue($this->room_revenue['value'], $this->room_revenue['name']);
        $this->house_remove_room_revenue();
    }

    private function house_check_room_revenue($amount, $name) {
        $room_revenue_amount = $this->execute(array('script' => 'return $(".house-account-transactions tr td:contains(\'' . $name . '\')").last().closest("tr").find(".credit").text();', 'args' => array()));
        $this->assertEquals($amount, $room_revenue_amount);
        sleep(10);
    }

    private function house_remove_room_revenue() {
        $el = $this->execute(array('script' => 'return window.$(".house-account-transactions tr td:contains('.$this->room_revenue['name'].')").closest("tr").find(".void-transaction").get(0);', 'args' => array()));
        $el = $this->elementFromResponseValue($el);
        $el->click();

        $this->waitForElement("#void_ha_transaction .btn-void", 15000, 'jQ')->click();
        sleep(5);
    }

    private function house_add_room_revenue_with_TX() {
        $this->waitForElement(".add_or_adjust_charge i", 15000, 'css')->click();
        $this->waitForElement(".add_or_adjust_charge .add-room-revenue-btn", 15000, 'css')->click();
        $this->waitForElement("#ha_add_room_revenue_box [name='amount']", 15000, 'jQ')->clear();
        $this->waitForElement("#ha_add_room_revenue_box [name='amount']", 15000, 'jQ')->value($this->room_revenue['value']);

        for ($i = 0; $i < 6; $i++) {
            $this->execute(array('script' => "return $('#ha_add_room_revenue_box .fees_and_taxes_tpl li a')[".$i."].click();", 'args' => array()));
        }

        $this->waitForElement("#ha_add_room_revenue_box .add_room_revenue", 15000, 'css')->click();
        sleep(5);
    }

    private function house_add_room_revenue_without_TX() {
        $this->waitForElement(".add_or_adjust_charge i", 15000, 'css')->click();
        $this->waitForElement(".add_or_adjust_charge .add-room-revenue-btn", 15000, 'css')->click();
        $this->waitForElement("#ha_add_room_revenue_box [name='amount']", 15000, 'jQ')->clear();
        $this->waitForElement("#ha_add_room_revenue_box [name='amount']", 15000, 'jQ')->value($this->room_revenue['value']);

        $this->waitForElement("#ha_add_room_revenue_box .add_room_revenue", 15000, 'css')->click();
        sleep(10);
    }

    private function house_check_room_revenue_with_taxes_and_fees() {
        foreach ($this->taxes_and_fees as $tax_or_fee) {
            $this->house_check_taxes_and_fess($tax_or_fee);

            if(!empty($tax_or_fee['tax_on_fee'])) {
                foreach ($tax_or_fee['tax_on_fee'] as $tax_on_fee) {
                    $this->house_check_taxes_and_fess($tax_on_fee);
                }
            }
        }
        sleep(10);
    }

    private function house_check_taxes_and_fess($tax_or_fee) {
        $amount = $this->execJS("
            var amount = '';
            $('.house-account-transactions tr td:contains(\"".$tax_or_fee['name']."\")').each(function(index, value) {
                var name_tax_or_fee = $(this).text();
                if (name_tax_or_fee == \"".$tax_or_fee['name']."\") {
                    amount = $(this).closest('tr').find('td.credit').text();
                }
            });
            return amount;
        ");

        $this->assertEquals($amount, $tax_or_fee['expecting_room_revenue_value']);
    }

    private function add_house_account() {
        $this->url($this->_prepareUrl($this->house_accounts_url));
        $this->waitForLocation($this->_prepareUrl($this->house_accounts_url));
        $this->betLoaderWaiting();
        $this->waitForElement('.add-house-account-btn', 15000, 'jQ')->click();
        $this->waitForElement('#house_account_name', 15000, 'jQ')->value('selenium test '.rand(0, 10000));
        $this->waitForElement('.save_house_account', 15000, 'jQ')->click();
        $this->betLoaderWaiting();
        $this->waitForElement('.view_details:visible:last', 15000, 'jQ')->click();
    }

    private function all_remove() {
        $this->execute(array('script' => "return BET.navigation.url('reservations');", 'args' => array()));
        $this->waitForElement(".reservations-table .res-action i", 15000, 'jQ')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 30000, 'css')->click();
        sleep(5);

        $this->fees_and_taxes_remove();
        $this->rate_delAllRates($this->room_types);
        $this->roomtype_delRoomType($this->rmt);
        sleep(5);
    }

    private function RoomRevenue_with_default_taxes_and_fees() {
        $this->create_room_revenue_with_default_taxes_and_fees($this->room_revenue['value']);
        $this->check_room_revenue($this->room_revenue['name'], $this->room_revenue['expecting_value']);
        $this->check_invoices();
        $this->check_room_revenue_with_taxes_and_fees();
        $this->remove_room_revenue($this->room_revenue['name']);
    }

    private function RoomRevenue_with_customs_taxes_and_fees() {
        $this->create_room_revenue_with_customs_taxes_and_fees($this->room_revenue['value']);
        $this->check_room_revenue($this->room_revenue['name'], $this->room_revenue['expecting_value']);
        $this->check_invoices();
        $this->check_room_revenue_with_taxes_and_fees();
        $this->remove_room_revenue($this->room_revenue['name']);
    }

    private function RoomRevenue_without_taxes_and_fees() {
        $this->create_room_revenue_without_taxes_and_fees($this->room_revenue['value']);
        $this->check_room_revenue($this->room_revenue['name'], $this->room_revenue['value']);
        $this->remove_room_revenue($this->room_revenue['name']);
    }

    private function create_room_revenue_without_taxes_and_fees($amount) {
        $this->create_room_revenue($amount);
        $this->waitForElement("#add-new-room-revenue-modal #add-room-revenue-btn", 15000, 'css')->click();
        sleep(10);
    }

    private function create_room_revenue_with_customs_taxes_and_fees($amount) {
        $this->create_room_revenue($amount);

        for ($i = 0; $i < 6; $i++) {
            $this->execute(array('script' => "return $('#add-new-room-revenue-modal .fees_and_taxes_tpl li a')[".$i."].click();", 'args' => array()));
        }

        $this->waitForElement("#add-new-room-revenue-modal #add-room-revenue-btn", 15000, 'css')->click();
        sleep(10);
    }

    private function create_room_revenue_with_default_taxes_and_fees($amount) {
        $this->create_room_revenue($amount);

        $this->execJS('$("#add-new-room-revenue-modal #checkbox-set_as_default").click();');
        $this->waitForElement("#add-new-room-revenue-modal #add-room-revenue-btn", 15000, 'css')->click();
        sleep(10);
    }

    private function create_room_revenue($amount) {
        $this->execute(array('script' => "return BET.navigation.url('reservations');", 'args' => array()));
        $this->waitForElement(".reservations-table .res-guest a", 15000, 'jQ')->click();
        $this->waitForElement('[href=\'#rs-folio-tab\']', 15000, 'jQ')->click();
        sleep(5);
        $this->waitForElement('#layout #rs-folio-tab-content .btn-group:eq(1)', 15000, 'jQ')->click();
        $this->waitForElement("#layout #rs-folio-tab-content .add-room-revenue-btn", 15000, 'css')->click();
        $this->waitForElement("#add-new-room-revenue-modal [name='amount']", 15000, 'jQ')->clear();
        $this->waitForElement("#add-new-room-revenue-modal [name='amount']", 15000, 'jQ')->value($amount);
    }

    private function check_room_revenue($name, $amount) {
        $room_revenue_amount = $this->execute(array('script' => 'return BET.langs.parseCurrency($("#rs-folio-tab-content tr td:contains(\''.$name.'\')").closest("tr").find(".debit").text(), true);', 'args' => array()));
        $this->assertEquals($amount, $room_revenue_amount);
        sleep(10);
    }

    private function check_room_revenue_with_taxes_and_fees() {
        foreach ($this->taxes_and_fees as $tax_or_fee) {
            $this->check_taxes_and_fess($tax_or_fee);

            if(!empty($tax_or_fee['tax_on_fee'])) {
                foreach ($tax_or_fee['tax_on_fee'] as $tax_on_fee) {
                    $this->check_taxes_and_fess($tax_on_fee);
                }
            }
        }
        sleep(10);
    }

    private function check_taxes_and_fess($tax_or_fee) {
        $amount = $this->execJS("
            var amount = '';
            $('.rs-transactions-table tr:contains(\"".$tax_or_fee['name']."\")').each(function(index, value) {
                var name_tax_or_fee = $(this).find('.type').text();
                if (name_tax_or_fee == \"".$tax_or_fee['name']."\") {
                    amount = $(this).find('td.debit').text();
                }
            });
            return amount;
        ");

        $this->assertEquals($amount, $tax_or_fee['expecting_room_revenue_value']);
    }

    private function remove_room_revenue($name) {
        sleep(5);
        $el = $this->execute(array('script' => 'return window.$("#layout #rs-folio-tab-content tr td:contains('.$name.')").closest("tr").find(".show-popup-vs-dropdown").get(0);', 'args' => array()));
        $el = $this->elementFromResponseValue($el);
        $el->click();

        $this->waitForElement(".transactions_actions_popover .void-transaction", 15000, 'jQ')->click();
        $this->waitForElement("#void_transaction .btn-add-new-product-save-void", 15000, 'jQ')->click();

        sleep(5);
    }

    private function check_invoices() {
        $this->waitForElement("#rs-totals-container button", 15000, 'jQ')->click();
        sleep(3);

        foreach ($this->taxes_and_fees as $tax_or_fee) {
            $total = $this->execJS("
                var total = '';
                $('#rs-totals-container .rs-totals-table tr td:contains(\"" . $tax_or_fee['name'] . "\")').each(function(index, value) {
                    var name_tax_or_fee = $(this).text();
                    if (name_tax_or_fee == \"" . $tax_or_fee['name'] . "\") {
                        total = BET.langs.parseCurrency($(this).closest('tr').find('td.price').text(), true);
                    }
                });
                return total;
            ");

            $this->assertEquals($total, $tax_or_fee['total']);
        }
        sleep(3);
    }

    private $room_types = array(
        'name' => 'room type selenium 1',
        'rooms' => 30,
        'room_type_descr_langs' => 'room type selenium 1'
    );

    private $std_intervals = array(
        'i1' => array(
            'name' => 'rate 1',
            'start' => '+0 day',
            'end' => '+30 day',
            'value_today' => 100
        )
    );

    private $room_revenue = array(
        'name' => 'Room Revenue - Cancellation',
        'value' => '12,00',
        'expecting_value' => '9,16'
    );

    private $rmt = array(
        'name' => 'room type selenium 1',
        'rooms' => 1,
        'room_type_descr_langs' => 'room types used for selenium testing room revenue'
    );

    private $reservation = array(
        'first_name' => 'SE First Name',
        'last_name' => 'SE Last Name',
        'email' => 'selenium@test.test',
        'phone' => '+1234567890',
        'country' => 'UA',
        'payment' => 'ebanking'
    );

    private $taxes_and_fees = array(
        'fee_percentage_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Exc',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_room_revenue_value' => '1,20',
            'total' => '11.20'
        ),
        'fee_percentage_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Inc',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_room_revenue_value' => '0,92',
            'total' => '8.55'
        ),
        'tax_percentage_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Exc',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_room_revenue_value' => '1,20',
            'total' => '11.20'
        ),
        'tax_percentage_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Inc',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_room_revenue_value' => '0,92',
            'total' => '8.55'
        ),
        'tax_fixed_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Exc on Fee Percentage Exc',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_room_revenue_value' => '1,20',
            'total' => '12.32',
            'tax_on_fee' => array(
                array(
                    'name' => 'Tax Percentage Exc on Fee Percentage Exc->Fee Percentage Exc',
                    'type' => 'Fee Percentage Exc',
                    'expecting_room_revenue_value' => '0,12'
                )
            )
        ),
        'tax_fixed_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Inc on Fee Percentage Inc',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_room_revenue_value' => '0,92',
            'total' => '9.40',
            'tax_on_fee' => array(
                array(
                    'name' => 'Tax Percentage Inc on Fee Percentage Inc->Fee Percentage Inc',
                    'type' => 'Fee Percentage Inc',
                    'expecting_room_revenue_value' => '0,09'
                )
            )
        ),
    );

    private function preparation() {
        $this->createRoomType();
        $this->createBookingIntervals();
        $this->createBookingTaxes_and_Fees();
        $this->linkTaxesOnTheSourcePage();
        $this->create_reservation();
    }

    public function createRoomType(){
        $room_type_id = $this->roomtype_addRoomType($this->rmt);
        return array_merge($this->rmt, array('room_type_id' => $room_type_id));
    }

    private function createBookingIntervals() {
        $this->setDefaultRates($this->room_types);
    }

    private function setDefaultRates($room_type) {
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

        for ($i = 0; $i < 6; $i++) {
            $this->execute(array('script' => "return $('#modal_primary_source .ms-parent.source_taxes .md-checkbox input')[".$i."].click();", 'args' => array()));
        }

        $this->byJQ('#modal_primary_source .btn-primary.blue.edit')->click();
        $this->waitForElement("#layout .sources-table", 15000, 'css');
        sleep(5);
    }

    private function create_reservation() {
        $this->execute(array('script' => "return BET.navigation.url('reservations/create');", 'args' => array()));
        $this->waitForElement("#layout .sources-groups .dropdown-toggle", 15000, 'css')->click();
        $this->waitForElement("#layout .sources-groups .dropdown-menu li:contains('Website')", 15000, 'jQ')->click();
        $this->waitForElement('#layout .find-availability', 15000, 'jQ')->click();
        $this->waitForElement("#layout .rooms-content .availability-row:contains(".$this->room_types['name'].") .add-rooms-to-cart", 15000, 'jQ')->click();
        $this->waitForElement('.find-availability-footer .reservation-stage-forward')->click();
        $this->execJS("$('#layout .customer-form #first_name_primary').val('".$this->reservation['first_name']."');");
        $this->execJS("$('#layout .customer-form #last_name_primary').val('".$this->reservation['last_name']."');");
        $this->execJS("$('#layout .customer-form #guest_email_primary').val('".$this->reservation['email']."');");
        $this->waitForElement(".reservation-stage-forward[data-stage='confirm-and-pay']", 15000, 'jQ')->click();
        $this->execJS('$(".confirm-and-pay-footer .confirm-reservation:visible").click();');
        $this->waitForElement(".confirm-reservation[data-send-email=0]", 15000, 'jQ')->click();
        sleep(30);
    }

    private function fees_and_taxes_remove() {
        $this->execute(array('script' => "return BET.navigation.url('fees_and_taxes');", 'args' => array()));
        $this->waitForElement('#layout .fees-and-taxes-table tr .delete-tax', 15000, 'css');

        $cnt = $this->execute(array(
            'script' => 'return $("#layout .fees-and-taxes-table .delete-tax").length;',
            'args' => array()
        ));

        for ($i = $cnt-1; $i > -1; $i--) {
            $this->execute(array('script' => "return $('#layout .fees-and-taxes-table .delete-tax')[".$i."].click();", 'args' => array()));
            $this->waitForElement('#confirm_delete .btn_delete', 15000, 'css')->click();
            $this->waitForElement('#layout .fees-and-taxes-table', 15000, 'css');
        }
    }
}