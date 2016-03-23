<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class change_rate_on_one_day extends base_rates{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_reserva_code}';
    private $calendar_url = 'http://{server}/connect/{property_id}#/calendar';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99',
        'start' => 'now',
        'end' => '+1 days',
        'min' => '2',
        'edit_end_day' => '+12 days'
    );

    private $price = 778;
    public function testSteps(){
        $test = $this;


        //$this->setupInfo('', '', '', 366);
        $this->setupInfo('PMS_user');

        $this->loginToSite();

        $url = $this->_prepareUrl($this->calendar_url);
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


        $el = $this->waitForElement('.room_type.selected td.today .day_price a', 15000, 'jQ');
        $el->click();
        $input = $this->waitForElement('.room_type.selected td.today .day_price .change-price-input', 15000, 'jQ');
        $input->clear();
        $input->value($this->price);
        $popover = $this->getAttribute($input, 'aria-describedby');
        print_r($popover);

        $click_price = $this->waitForElement('#'.$popover.' .confirm-change-price', 15000, 'jQ');
        $click_price->click();

        /*     $this->addRate($this->interval);

             $room_type_id = $this->execute(array('script' => "return window.$('#tab_0 [name=room_type_id]').val()", 'args' => array()));
             $room_type = $this->execute(array('script' => "return window.TAFFY(BET.DB().select('room_types')[0])({room_type_id: String(".$room_type_id.")}).get()[0]", 'args' => array()));
             $rate_id = $this->execute(array('script' => "return window.$('#tab_0 [name=rate_id]').val()", 'args' => array()));
             $interval =$this->interval;

             $arr = $this->getAvailability($this->convertDateToSiteFormat($interval['start'],'Y-m-d'),$this->convertDateToSiteFormat($interval['end'],'Y-m-d'),$room_type_id);
             $availability = $arr->data[0]->rates->{$rate_id}->{$this->convertDateToSiteFormat($interval['start'],'Y-m-d')}->avail;

             print_r($availability);

             $this->delRate();*/

        $this->loginToMA();
        $el = $this->waitForElement('.menu-item-availability a', 15000, 'css');
        $el->click();
        $this->waitForElement('.availTableWrapper', 15000, 'css');
        $rate = $this->waitForElement('/html/body/div[2]/div/div/div/div[9]/div[3]/table/tbody/tr[1]/td[2]/input[2]', 15000, 'xpath');

        $test->assertEquals($rate->value(), $this->price);
        $this->delRate();
        $this->loginToMA();
        $el = $this->waitForElement('.menu-item-availability a', 15000, 'css');
        $el->click();
        $this->waitForElement('.availTableWrapper', 15000, 'css');
        $rate = $this->waitForElement('/html/body/div[2]/div/div/div/div[9]/div[3]/table/tbody/tr[1]/td[2]/input[2]', 15000, 'xpath');
        $test->assertEquals($rate->value(), '0');
    }

}
?>
