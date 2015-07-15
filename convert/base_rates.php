<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class base_rates extends test_restrict{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';

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
        $value = $this->convertDateToSiteFormat($interval['end']);
        $this->byName('end_date')->clear();
        $this->byName('end_date')->value($value);
        $this->byCssSelector('.new_interval_form')->click();

        $l = $this->execute(array('script' => "return window.$('#tab_0 .define_week_days td:not(._hide) input').length", 'args' => array()));
        for($i=0;$i<$l;$i++){
            $el = $this->byJQ("#tab_0 .define_week_days td:not(._hide) input:eq(".$i.")");
            $el->clear();
            $el->value($interval['value_today']);
        }


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

    public function updateRate($interval){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#tab_0', 15000, 'css')->click();
        $this->byJQ('#tab_0 .intervals-table tr.r_rate:last .interval_edit')->click();
        $this->byName('end_date')->click();
        $this->byCssSelector('.new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['edit_end_day']);
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

    public function avalCheck($interval, $room_type_id, $rate_id, $room_type){
        $arr = $this->getAvailability($this->convertDateToSiteFormat($interval['start'],'Y-m-d'),$this->convertDateToSiteFormat($interval['end'],'Y-m-d'),$room_type_id);
        $availability = $arr->data[0]->rates->{$rate_id}->{$this->convertDateToSiteFormat($interval['start'],'Y-m-d')}->avail;

        //////////////////////////////////////
        $rate_obj = $rate = $arr->data[0]->rates->{$rate_id};
        foreach($rate_obj as $key => $el) {
            $rate = $el->rate;
            echo "el=".$rate;
            $this->assertGreaterThan(floatval(1), floatval($rate));
        }
        //////////////////////////////////////

        $booking_room_real = $room_type['room_type_max_rooms']  - ($room_type['room_type_capacity'] - $availability);

        $this->url($this->_prepareUrl($this->reservas_url));
        $this->waitForLocation($this->_prepareUrl($this->reservas_url));
        $this->waitForElement('.available_rooms', 15000, 'css');
        $booking_room = $this->execute(array('script' => "return window.$('.available_rooms .room_types [data-room_type_id=".$room_type_id."][data-is_package=0] .roomtype select.rooms_select option').length", 'args' => array()));
        $booking_room--;
        //////////////////////////////////////
        echo 'real= '.$booking_room_real;
        echo 'on_booking= '.$booking_room;
        $this->assertEquals($booking_room_real,$booking_room);
        //////////////////////////////////////
    }
}
?>
