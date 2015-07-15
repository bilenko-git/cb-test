<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class add_rate extends test_restrict{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99',
        'start' =>'now',
        'end' =>'+3'
    );
    public function testSteps(){
        $step = $this;

        $this->setupInfo('', '', '', 366);
        $this->loginToSite();


        $this->addRate($this->interval);

        $room_type_id = $this->execute(array('script' => "return window.$('#tab_0 [name=room_type_id]').val()", 'args' => array()));
        $room_type = $this->execute(array('script' => "return window.TAFFY(BET.DB().select('room_types')[0])({room_type_id: String(".$room_type_id.")}).get()[0]", 'args' => array()));
        $rate_id = $this->execute(array('script' => "return window.$('#tab_0 [name=rate_id]').val()", 'args' => array()));
        $arr = $this->getAvailability($this->convertDateToSiteFormat($this->interval['start'],'Y-m-d'),$this->convertDateToSiteFormat($this->interval['end'],'Y-m-d'),$room_type_id);
        $availability = $arr->data[0]->rates->{$rate_id}->{$this->convertDateToSiteFormat($this->interval['start'],'Y-m-d')}->avail;


        $booking_room_real = $room_type['room_type_max_rooms']  - ($room_type['room_type_capacity'] - $availability);
        if ($booking_room_real < 0) {
            $booking_room_real = 0;
        }

        $this->url($this->_prepareUrl($this->reservas_url));
        $this->waitForLocation($this->_prepareUrl($this->reservas_url));
        $this->waitForElement('.available_rooms', 15000, 'css');
        $booking_room = $this->execute(array('script' => "return window.$('.available_rooms .room_types [data-room_type_id=".$room_type_id."][data-is_package=0] .roomtype select.rooms_select option').length", 'args' => array()));
        $booking_room--;

        echo 'real= '.$booking_room_real;
        echo 'on_booking= '.$booking_room;
        $this->assertEquals($booking_room_real,$booking_room);

        $this->delRate();

    }
    public function addRate($interval){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#tab_0', 15000, 'css')->click();
        $add_new_rate_plan = $this->waitForElement('#tab_0 .add_interval', 15000, 'css');
        $add_new_rate_plan->click();
        $this->byName('interval_name')->value($interval['name']);
        $this->byName('start_date')->click();
        $this->byCssSelector('.new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $this->byName('start_date')->value($value);
        $this->byName('end_date')->click();
        $this->byCssSelector('.new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $this->byName('end_date')->value($value);
        $this->byCssSelector('.new_interval_form')->click();

        $el = $this->byJQ(".define_week_days td:not(._hide) input");
        $el->clear();
        $el->value($interval['value_today']);

        $this->byCssSelector('.new_interval_form a.save_add_interval')->click();

        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');
    }

    public function delRate(){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#tab_0', 15000, 'css')->click();
        $this->byJQ('#tab_0 .intervals-table tr.r_rate:last .interval_delete')->click();
        $this->waitForElement('#confirm_delete', 50000, 'css');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();
        $save = $this->waitForElement('#panel-save .btn-save', 15000, 'css');
        $save->click();
        $this->waitForElement('.toast-bottom-left', 50000, 'css');

    }
}
?>
