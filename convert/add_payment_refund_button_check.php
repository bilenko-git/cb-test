<?php
//HOTELS-10008
namespace MyProject\Tests;
require_once 'test_restrict.php';

class add_payment_refund_button_check extends test_restrict{
    private $reservation_list_page = 'http://{server}/connect/{property_id}#/newreservations';
    private $reservation_detail_page = 'http://{server}/connect/{property_id}#/newreservations/{res_id}';

    public function testButtonDisappear(){
        $this->go_to_reservations_page();
        $this->go_to_any_reservation();

        //check at once button dissapeared
        $this->waitForElement('#layout .btn-add-payment', 15000, 'jQ')->click();

        $check = $this->checkDisplayedButtons();
        $this->assertEquals(-2, $check, 'Buttons are visible');

        //check buttons appeared back when clicking [view,refund,void] payment in table, [cancel] on adding form, [view credit cards]
        $hide_buttons_selectors = array(
            '.view-booking-payment',
            '.refund-payment',
            '.void-booking-payment',
            '.btn-cancel-payment',
            '.btn-view-credit-cards'
        );

        foreach($hide_buttons_selectors as $hs){
            try {
                $el = $this->waitForElement($hs, 5000, 'jQ', true, false);
                if ($el) {
                    $el->click();
                    $check = $this->checkDisplayedButtons();
                    $this->assertEquals(2, $check, 'Buttons are not visible. Error: ' . $check);
                }

                $this->waitForElement('#layout .btn-add-payment', 15000, 'jQ')->click();
            }catch(\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e){
                echo 'Some parts of test was skipped. Incomplete default data.';
            }
        }
    }

    public function checkDisplayedButtons(){
        $check = $this->waitForElement('#layout .btn-add-payment', 15000, 'jQ', false)->displayed() ? 1: -1;
        $check += $this->waitForElement('#layout .btn-add-refund-payment', 15000, 'jQ', false)->displayed() ? 1 : -1;
        return $check;
    }

    public function go_to_any_reservation(){
        $res = $this->waitForElement('#layout .reservations-table a.view_summary[data-id]:first', 30000, 'jQ');
        $res_id = $this->getAttribute($res, 'data-id');
        $detail_url = $this->_prepareUrl($this->reservation_detail_page);
        $detail_url = str_replace('{res_id}', $res_id, $detail_url);

        $this->url($detail_url);
        $this->waitForLocation($detail_url);
    }

    public function go_to_reservations_page(){
        //$this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);//for 31 hotel
        //  $this->setupInfo('', 'aleksandr.brus+20150715@cloudbeds.com', 'KNS16111988', 412);//for 412 - my demo hotel on dev3

        $this->loginToSite();
        $list_page = $this->_prepareUrl($this->reservation_list_page);
        $this->url($list_page);
        $this->waitForLocation($list_page);
    }
}?>