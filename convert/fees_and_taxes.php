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
        $this->loginToSite();
        $this->prepareDataBooking();
        $this->loginToSite();
        $this->linkTaxesOnTheSourcePage();
        $this->goToTheBookingPage();
        $this->checkBookingFees();
        $this->makeBookingReservation();
        $this->checkBookingReservationTaxes();
        $this->goToTheReservation();
        $this->checkFolioAfterBooking();
        $this->removeBookingReservation();
        $this->makeFrontDeskReservation();
        $this->checkFolioAfterBooking();
        $this->removeDataBooking();
    }

    // public function test_frontdesk_page_calculations() {
    //     $this->setupInfo('PMS_user');
    //     $this->loginToSite();
    //     // $this->prepareDataBooking();
    //     // $this->loginToSite();
    //     // $this->linkTaxesOnTheSourcePage();
    //     // $this->removeDataBooking();
    // }

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
            $fee_amount = $this->execute(array('script' => 'return CBBooking.parseCurrency($(".taxes_and_fees .row:contains(\''.$fee['name'].'\')").find(".sum").text(), true);', 'args' => array()));
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
            '#country' => $this->reservation['country']
        ), $context);
        $this->execJS('$("#ebanking").click();');
        $this->execJS('$("#agree_terms").click();');
        $this->execJS('$(".finalize").click();');
    }

    private function makeFrontDeskReservation() {
        $this->startDate = date('Y-m-d', strtotime('next monday'));
        $this->endDate = date('Y-m-d', strtotime('+10 day', strtotime($this->startDate)));
        $this->execute(array('script' => "return BET.navigation.url('reservations/create');", 'args' => array()));
        $this->waitForElement("#layout .sources-groups .dropdown-toggle", 15000, 'css')->click();
        $this->waitForElement("#layout .sources-groups .dropdown-menu li:contains('Website')", 15000, 'jQ')->click();
        $this->execJS('$("[name=\'checkin_date\']", "#layout .find-availability-stage .res-controls-header").val(BET.langs.date.format("'.$this->startDate.'"));');
        $this->execJS('$("[name=\'checkout_date\']", "#layout .find-availability-stage .res-controls-header").val(BET.langs.date.format("'.$this->endDate.'"));');
        $this->waitForElement('#layout .find-availability')->click();
        foreach($this->room_types as $room_type) {
            $this->waitForElement('#layout .rooms-content .availability-row');
            $this->execute(array('script' => 'return $("#layout .rooms-content .availability-row:contains(\''.$room_type['name'].'\') .selected-rooms").addClass("open");', 'args' => array()));
            $this->waitForElement(".selected-rooms li[data-original-index=2]", 15000, 'jQ')->click();
            $this->execute(array('script' => 'return $("#layout .rooms-content .availability-row:contains(\''.$room_type['name'].'\') .selected-rooms").removeClass("open");', 'args' => array()));
            $this->execute(array('script' => 'return $("#layout .rooms-content .availability-row:contains(\''.$room_type['name'].'\') .add-rooms-to-cart").click();', 'args' => array()));
        }
        $this->waitForElement('.totals-footer .total-item.fees i')->click();
        foreach($this->fees as $fee) {
            if ($fee['type_of'] == 'fee') {
                $fee_amount = $this->execute(array('script' => 'return BET.langs.parseCurrency($(".fee-tax-tooltip-table tr:contains(\''.$fee['name'].'\')").find("td:eq(1)").text(), true);', 'args' => array()));
                $this->assertEquals($fee['expecting_booking_value'], $fee_amount);
            }
        }

        $this->waitForElement('.totals-footer .total-item.taxes i')->click();
        foreach($this->fees as $fee) {
            if ($fee['type_of'] == 'tax') {
                $fee_amount = $this->execute(array('script' => 'return BET.langs.parseCurrency($(".fee-tax-tooltip-table tr:contains(\''.$fee['name'].'\')").find("td:eq(1)").text(), true);', 'args' => array()));
                $this->assertEquals($fee['expecting_booking_value'], $fee_amount);
            }
        }

        $this->waitForElement('.find-availability-footer .reservation-stage-forward')->click();
        $this->execJS("$('#layout .customer-form #first_name_primary').val('".$this->reservation['first_name']."');");
        $this->execJS("$('#layout .customer-form #last_name_primary').val('".$this->reservation['last_name']."');");
        $this->execJS("$('#layout .customer-form #guest_email_primary').val('".$this->reservation['email']."');");
        $this->waitForElement(".reservation-stage-forward[data-stage='confirm-and-pay']", 15000, 'jQ')->click();

        foreach($this->fees as $fee) {
            $fee_amount = $this->execute(array('script' => 'return BET.langs.parseCurrency($(".confirm-pay-totals .rs-totals-table tr:contains(\''.$fee['name'].'\')").find(".price").text(), true);', 'args' => array()));
            $this->assertEquals($fee['expecting_booking_value'], $fee_amount);
        }

        $this->execJS('$(".confirm-and-pay-footer .confirm-reservation:visible").click();');
        $this->waitForElement(".confirm-reservation[data-send-email=0]", 15000, 'jQ')->click();
        sleep(30);
    }

    private function checkBookingReservationTaxes() {
        $this->waitForElement(".for_saved_items", 30000, 'css');
        $url = $this->_prepareUrl($this->bookingDoneUrl);
        $this->url($url);
        $this->waitForLocation($url);
        $this->waitForElement(".for_saved_items", 30000, 'css');
        $this->reservation_id = $this->execute(array('script' => 'return $(".reserve_number").text();', 'args' => array()));
        foreach($this->fees as $fee) {
            $fee_amount = $this->execute(array('script' => 'return CBBooking.parseCurrency($(".reserve_total .row.sub_fees:contains(\''.$fee['name'].'\')").find(".grand_total").text(), true);', 'args' => array()));
            $this->assertEquals($fee['expecting_booking_value'], $fee_amount);
        }
    }

    private function goToTheReservation() {
        $url = $this->_prepareUrl($this->siteUrl).'#/reservations/r'.$this->reservation_id;
        $this->url($url);
        $this->waitForLocation($url);
        $this->waitForBETLoaded();
    }

    private function checkFolioAfterBooking() {
        $this->waitForElement('#rs-totals-container .rs-show-res-totals')->click();
        foreach($this->fees as $fee) {
            $fee_amount = $this->execute(array('script' => 'return BET.langs.parseCurrency($("#rs-totals-container .rs-totals-dropdown tr:contains(\''.$fee['name'].'\')").find(".price").text(), true);', 'args' => array()));
            $this->assertEquals($fee['expecting_booking_value'], $fee_amount);
        }
        $this->waitForElement('#reservation-tabs [data-action-name="show_folio_tab"]')->click();
        $this->execJS('$("#rs-folio-tab-content .posted-or-not .bootstrap-select").addClass("open");');
        $this->waitForElement("#rs-folio-tab-content .posted-or-not .bootstrap-select li[data-original-index=1]", 15000, 'jQ')->click();
        $this->execJS('$("#rs-folio-tab-content .posted-or-not .bootstrap-select").removeClass("open");');
        $this->waitForElement("#layout #apply-folio-filter")->click();
        $this->waitForElement(".rs-transactions-table tr", 15000, 'css');
        foreach($this->fees as $fee) {
            $fee_assert = $this->execJS("
                var assert_flag = true;
                $('.rs-transactions-table tr:contains(\"".$fee['name']."\")').each(function(index, value) {
                    var fee_amount = BET.langs.parseCurrency($(this).find('td.debit').text());
                    if (parseFloat(fee_amount).toFixed(2) != parseFloat(\"".$fee['expecting_every_day_value']."\").toFixed(2)) {
                        assert_flag = false;
                    }
                }); return assert_flag;
            ");
            $this->assertEquals($fee_assert, true);
        }
    }

    private function removeDataBooking() {
        $this->removeBookingReservation();
        $this->removeBookingFees();
        $this->removeBookingRoomTypes();
    }

    private function removeBookingReservation() {
        $this->waitForElement('#layout .delete-button-reservation', 30000, 'css')->click();
        $this->waitForElement('#confirm_delete .btn_delete', 30000, 'css')->click();
        $this->waitForElement('#layout #list-reservations', 30000, 'css');
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
    private $bookingDoneUrl = 'http://{server}/reservation/confirmation';
    private $sourceUrl = 'http://{server}/connect/{property_id}#/sources';

    private $fees = array(
        'fee_percentage_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Exc',
            'name_changed' => 'Fee Percentage Exc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00',
            'expecting_every_day_value' => '10.00'
        ),
        'fee_percentage_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Percentage Inc',
            'name_changed' => 'Fee Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '400.00',
            'expecting_every_day_value' => '6.67'
        ),
        'fee_fixed_exl' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Exc',
            'name_changed' => 'Fee Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00',
            'expecting_every_day_value' => '10.00'
        ),
        'fee_fixed_inc' => array(
            'type_of' => 'fee',
            'name' => 'Fee Fixed Inc',
            'name_changed' => 'Fee Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '600.00',
            'expecting_every_day_value' => '10.00'
        ),
        'fee_fixed_accm' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Accomodations',
            'name_changed' => 'Fee Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '60.00',
            'expecting_every_day_value' => '10.00'
        ),
        'fee_fixed_res' => array(
            'type_of' => 'fee',
            'name' => 'Fee Per Reservations',
            'name_changed' => 'Fee Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '10.00',
            'expecting_every_day_value' => '10.00'
        ),
        'tax_percentage_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Exc',
            'name_changed' => 'Tax Percentage Exc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00',
            'expecting_every_day_value' => '10.00'
        ),
        'tax_percentage_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Percentage Inc',
            'name_changed' => 'Tax Percentage Inc Changed',
            'amount_type' => 'percentage',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '400.00',
            'expecting_every_day_value' => '6.67'
        ),
        'tax_fixed_exl' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Exc',
            'name_changed' => 'Tax Fixed Exc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '600.00',
            'expecting_every_day_value' => '10.00'
        ),
        'tax_fixed_inc' => array(
            'type_of' => 'tax',
            'name' => 'Tax Fixed Inc',
            'name_changed' => 'Tax Fixed Inc Changed',
            'amount_type' => 'fixed',
            'amount' => '10',
            'type' => 'inclusive',
            'expecting_booking_value' => '600.00',
            'expecting_every_day_value' => '10.00'
        ),
        'tax_fixed_accm' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Accomodations',
            'name_changed' => 'Tax Per Accomodations Changed',
            'amount_type' => 'fixed_per_accomodation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '60.00',
            'expecting_every_day_value' => '10.00'
        ),
        'tax_fixed_res' => array(
            'type_of' => 'tax',
            'name' => 'Tax Per Reservations',
            'name_changed' => 'Tax Per Reservations Changed',
            'amount_type' => 'fixed_per_reservation',
            'amount' => '10',
            'type' => 'exclusive',
            'expecting_booking_value' => '10.00',
            'expecting_every_day_value' => '10.00'
        )
    );

    private $room_types = array(
        array(
            'name' => 'Room Type Fees And Taxes Check 1',
            'rooms' => 30,
            'room_type_descr_langs' => 'Room Type Fees And Taxes Check Description 1'
        ) , array(
            'name' => 'Room Type Fees And Taxes Check 2',
            'rooms' => 30,
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
