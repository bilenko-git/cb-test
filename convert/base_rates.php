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

        if (isset($interval['min'])){
            $this->byName('min_los')->value($interval['min']);
        }

        if (isset($interval['max'])){
            $this->byName('max_los')->value($interval['max']);
        }

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

    public function updateRate($interval, $click = false){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#tab_0', 15000, 'css')->click();
        $this->byJQ('#tab_0 .intervals-table tr.r_rate:last .interval_edit')->click();
        $this->byName('end_date')->click();
        $this->byCssSelector('.new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $this->byName('end_date')->clear();
        $this->byName('end_date')->value($value);
        $this->byCssSelector('.new_interval_form')->click();

        if($click) {
            $l = $this->execute(array('script' => "return window.$('.define_week_days td._hide').length", 'args' => array()));
            for ($i = 0; $i < $l; $i++) {
                $index = $this->execute(array('script' => "return window.$('.define_week_days td._hide:eq(0)').index()", 'args' => array()));
                $check = $this->byJQ('#tab_0 .define_week_days th:eq('. $index .') .md-checkbox label');
                $check->click();
            }
        }
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

    public function avalCheck($interval, $room_type_id, $rate_id, $room_type){
        $arr = $this->getAvailability($this->convertDateToSiteFormat($interval['start'],'Y-m-d'),$this->convertDateToSiteFormat($interval['end'],'Y-m-d'),$room_type_id);
        $availability = $arr->data[0]->rates->{$rate_id}->{$this->convertDateToSiteFormat($interval['start'],'Y-m-d')}->avail;

        unset($arr->data[0]->rates->{$rate_id}->{$this->convertDateToSiteFormat($interval['end'],'Y-m-d')});
        //print_r($arr->data[0]->rates->{$rate_id});
        $bool = false;
        //////////////////////////////////////
        $rate_obj = $arr->data[0]->rates->{$rate_id};
        foreach($rate_obj as $key => $el) {
            $rate = $el->rate;
            if ($el->avail < $availability){
                $availability = $el->avail;
            }
            if ($el->avail < 1){
                $bool = true;
                echo "----------".$el."------";
            }
            //echo "el".$key."=".$el->rate;
            if (floatval($rate) <= 0) {
               $bool = true;
                echo "----------".$el."------";
            }
        }
        $days = intval((strtotime($this->convertDateToSiteFormat($interval['end'],'Y-m-d')) - strtotime($this->convertDateToSiteFormat($interval['start'],'Y-m-d'))) / 86400);
        echo 'start='.$this->convertDateToSiteFormat($interval['start'],'Y-m-d');
        echo 'start='.$this->convertDateToSiteFormat($interval['end'],'Y-m-d');
       echo "day=".$days;
        if (((isset($interval['min']) && $days < $interval['min']) || (isset($interval['max']) && $days > $interval['max']))){
            $bool = true;
            echo "+++++++++day=".$days;
        }
        //////////////////////////////////////

        $booking_room_real = $room_type['room_type_max_rooms']  - ($room_type['room_type_capacity'] - $availability);

        $this->url($this->_prepareUrl($this->reservas_url));
        $this->waitForLocation($this->_prepareUrl($this->reservas_url));

        $this->byName('search_start_date')->click();
        $this->byCssSelector('#wizard')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $this->byName('search_start_date')->clear();
        $this->byName('search_start_date')->value($value);
        $this->byName('search_end_date')->click();
        $this->byCssSelector('#wizard')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $this->byName('search_end_date')->clear();
        $this->byName('search_end_date')->value($value);
        $this->byCssSelector('#wizard')->click();
        $this->byName('check_availability')->click();

        $this->waitForElement('.available_rooms', 15000, 'css');
        $booking_room = $this->execute(array('script' => "return window.$('.available_rooms .room_types [data-room_type_id=".$room_type_id."][data-is_package=0] .roomtype select.rooms_select option').length", 'args' => array()));
        echo "answer".$booking_room;
        if ($bool){
            $this->assertEquals(0,$booking_room);
        } else {
            $booking_room--;
            //////////////////////////////////////
            //echo 'real= ' . $booking_room_real;
            //echo 'on_booking= ' . $booking_room;
            $this->assertEquals($booking_room_real, $booking_room);
            //////////////////////////////////////
        }
    }
}
?>
