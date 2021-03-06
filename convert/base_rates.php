<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class base_rates extends test_restrict{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $roomType_url = 'http://{server}/connect/{property_id}#/roomTypes';
    private $reservas_url = 'http://{server}/reservas/{property_reserva_code}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';

    public function addRate($interval, $type = false){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->refresh();
        if (getenv('SELENIUM_LOCAL')) {
            $el = $this->byJQ(".progress-bar-background");
            $this->waitUntilVisible($el, 30000);
        }
        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
        } else {
            $this->waitForElement('.nav-tabs li.base:first a', 15000, 'jQ')->click();
        }
        $add_new_rate_plan = $this->waitForElement('#layout .add_interval', 15000, 'jQ');

        $add_new_rate_plan->click();
        $this->waitForElement('[name=interval_name]', 15000, 'jQ' )->value($interval['name']);
        $this->waitForElement('[name=start_date]', 15000, 'jQ')->click();
        $this->waitForElement('.new_interval_form', 15000, 'jQ')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $el = $this->waitForElement('[name=start_date]', 15000, 'jQ');
        $el->clear();
        $el->value($value);
        $this->waitForElement('[name=end_date]', 15000, 'jQ')->click();
        $this->waitForElement('.new_interval_form', 1500, 'jQ')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $el = $this->waitForElement('[name=end_date]', 15000, 'jQ');
        $el->clear();
        $el->value($value);
        $this->waitForElement('.new_interval_form', 1500, 'jQ')->click();

        if (isset($interval['min'])){
            $el= $this->waitForElement('[name=min_los]', 15000, 'jQ');
            $el->clear();
            $el->value($interval['min']);
        }

        if (isset($interval['max'])){
            $el= $this->waitForElement('[name=max_los]', 15000, 'jQ');
            $el->clear();
            $el->value($interval['max']);
        }

        if (isset($interval['arrival'])){
            $this->waitForElement('.new_interval_form .md-radio-inline:visible:eq(0) label:'.($interval['arrival'] ? 'first':'last'), 1500, 'jQ')->click();
        }
        if (isset($interval['departure'])){
            $this->waitForElement('.new_interval_form .md-radio-inline:visible:eq(1) label:'.($interval['departure'] ? 'first':'last'), 1500, 'jQ')->click();
        }

        $l = $this->execute(array('script' => "return window.$('.define_week_days td:not(._hide) input:visible').length", 'args' => array()));
        for($i=0;$i<$l;$i++){
            $el = $this->byJQ(".define_week_days td:not(._hide) input:visible:eq(".$i.")");
            $el->clear();
            $el->value($interval['value_today']);
        }


        $this->waitForElement('.new_interval_form a.save_add_interval', 1500, 'jQ')->click();

        $this->save();
    }

    public function copy_intervals($type = false){
        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
        } else {
            $this->waitForElement('.nav-tabs li.base:first a', 15000, 'jQ')->click();
        }
        sleep(2);
        $this->waitForElement('.copy_section.copy_rates:visible', 15000, 'jQ')->click();
        $this->waitForElement('.modal-content', 15000, 'jQ');
        $this->waitForElement('.modal-content .save_changes3', 15000, 'jQ')->click();
        $this->waitForElement('.room_rate_copy_confirm_modal', 15000, 'jQ');
        $this->waitForElement('.room_rate_copy_confirm_modal .save_changes3', 15000, 'jQ')->click();
        $this->waitForElement('.r_rate', 15000, 'jQ');
        $this->save();
    }

    public function delRate($type = false){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        sleep(2);
        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
            $this->waitForElement('#layout .intervals-table tr.r_rate:visible:last .interval_delete', 15000, 'jQ')->click();
        } else {
            $this->waitForElement('.nav-tabs li.base:first a', 15000, 'jQ')->click();
            $this->waitForElement('#layout .intervals-table tr.r_rate:visible:last .interval_delete', 15000, 'jQ')->click();
        }
        $this->waitForElement('#confirm_delete', 50000, 'css');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();
        $this->save();
    }

    public function delAllRates($type) {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));

        $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();

        $cnt = $this->execute(array('script' => 'return $(\'#layout .intervals-table tr.r_rate:last .interval_delete\').length', 'args' => array()));
        for($i = 0; $i < $cnt; $i++) {
            $this->waitForElement('#layout .intervals-table tr.r_rate:eq(' . $i . ') .interval_delete', 15000, 'jQ')->click();
        }

        $this->waitForElement('#confirm_delete', 50000, 'css');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();
        $this->save();
    }

    public function delRoomType($type){
        $this->url($this->_prepareUrl($this->roomType_url));
        $this->waitForLocation($this->_prepareUrl($this->roomType_url));
        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
            $this->waitForElement('.nav-tabs li .remove-tab', 15000, 'jQ')->click();
            $this->waitForElement('#confirm_modal .btn_ok', 15000, 'jQ')->click();
        }
        $this->save();

    }

    public function addRoomType($roomtype){
        $this->url($this->_prepareUrl($this->roomType_url));
        $this->waitForLocation($this->_prepareUrl($this->roomType_url));
        $this->waitForElement('.add-room-type-btn', 15000, 'css')->click();
        $el = $this->waitForElement('[name^=\'room_type_title_langs\']', 15000, 'jQ');
        $el->click();
        $el->value($roomtype['name']);
        $el = $this->waitForElement('[name=\'room_type_capacity\']', 15000, 'jQ');
        $el->clear();
        $el->value($roomtype['rooms']);
      /*  $el = $this->waitForElement('[name=\'room_type_max_rooms\']', 15000, 'jQ');
        $el->clear();
        $el->value($roomtype['rooms']);
        $el = $this->waitForElement('[name=\'room_type_bookable_limit\']', 15000, 'jQ');
        $el->clear();
        $el->value($roomtype['rooms']);
      */
        $el = $this->waitForElement('[name^=\'room_type_descr_langs\']', 15000, 'jQ');
        $el->clear();
        $el->value($roomtype['room_type_descr_langs']);
        $this->save();

      /*  return $this->execute(array(
            'script' => "return $('#layout .roomtype_tabs .tab-pane.active [name=room_type_id]');",
            'args' => array()
        ));*/
    }


    public function updateRate($interval, $click = false, $type = false){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
            $this->waitForElement('#layout .intervals-table tr.r_rate:visible:last .interval_edit', 15000, 'jQ')->click();
        } else {
            $this->waitForElement('.nav-tabs li.base:first a', 15000, 'jQ')->click();
            $this->waitForElement('#layout .intervals-table tr.r_rate:visible:last .interval_edit', 15000, 'jQ')->click();
        }
        $this->waitForElement('[name=interval_name]', 15000, 'jQ' )->value($interval['name']);
        $this->waitForElement('[name=start_date]', 15000, 'jQ')->click();
        $this->waitForElement('.new_interval_form', 15000, 'jQ')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $el = $this->waitForElement('[name=start_date]', 15000, 'jQ');
        $el->clear();
        $el->value($value);
        $this->waitForElement('[name=end_date]', 15000, 'jQ')->click();
        $this->waitForElement('.new_interval_form', 1500, 'jQ')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $el = $this->waitForElement('[name=end_date]', 15000, 'jQ');
        $el->clear();
        $el->value($value);
        $this->waitForElement('.new_interval_form', 1500, 'jQ')->click();

        if (isset($interval['min'])){
            $el = $this->waitForElement('[name=min_los]', 1500, 'jQ');
            $el->clear();
            $el->value($interval['min']);
        }

        if (isset($interval['max'])){
            $el = $this->waitForElement('[name=max_los]', 1500, 'jQ');
            $el->clear();
            $el->value($interval['max']);
        }

        if (isset($interval['arrival'])){
            $this->waitForElement('.new_interval_form .md-radio-inline:visible:eq(0) label:'.($interval['arrival'] ? 'first':'last'), 1500, 'jQ')->click();
        }
        if (isset($interval['departure'])){
            $this->waitForElement('.new_interval_form .md-radio-inline:visible:eq(1) label:'.($interval['departure'] ? 'first':'last'), 1500, 'jQ')->click();
        }
        if($click) {
            $l = $this->execute(array('script' => "return window.$('.define_week_days td._hide').length", 'args' => array()));
            for ($i = 0; $i < $l; $i++) {
                $index = $this->execute(array('script' => "return window.$('.define_week_days:visible td._hide:eq(0)').index()", 'args' => array()));
                $check = $this->byJQ('.define_week_days:visible th:eq('. $index .') .md-checkbox label');
                $check->click();
            }
        }
        $l = $this->execute(array('script' => "return window.$('.define_week_days:visible td:not(._hide) input').length", 'args' => array()));
        for($i=0;$i<$l;$i++){
            $el = $this->byJQ(".define_week_days:visible td:not(._hide) input:eq(".$i.")");
            $el->clear();
            $el->value($interval['value_today']);
        }
        $this->waitForElement('.new_interval_form a.save_add_interval', 15000, 'jQ')->click();

        $this->save();
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
              //  echo "----------".$el."------";
            }
            //echo "el".$key."=".$el->rate;
            if (floatval($rate) <= 0) {
               $bool = true;
              //  echo "----------".$el."------";
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

         if(!$this->execute(array('script' => "return BET.DB().select('booking_quantity_to_sell')[0] - 0", 'args' => array()))){
             $room_type['room_type_max_rooms'] = $room_type['room_type_capacity'];
        }

        $booking_room_real = $room_type['room_type_max_rooms']  - ($room_type['room_type_capacity'] - $availability);
       /* print_r('--------------------------------');
        print_r($this->execute(array('script' => "return BET.DB().select('booking_quantity_to_sell')[0]", 'args' => array())));
        print_r('--------------------');*/

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
