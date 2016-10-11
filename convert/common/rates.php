<?php

trait Rates {
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $roomType_url = 'http://{server}/connect/{property_id}#/roomTypes';
    private $reservas_url = 'http://{server}/reservas/{property_reserva_code}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';

    public function rates_add_rate($interval) {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#layout #tab_0', 15000, 'css')->click();
        $add_new_rate_plan = $this->waitForElement('#layout #tab_0 .add_interval', 15000, 'css');
        $r = $add_new_rate_plan->click();
        $this->waitForElement('#layout #tab_0 [name=interval_name]', 15000, 'css')->value($interval['name']);
        $this->waitForElement('[name=start_date]')->click();
        $this->byCssSelector('#layout #tab_0 .new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $this->waitForElement('[name=start_date]')->value($value);
        $this->waitForElement('[name=end_date]')->click();
        $this->byCssSelector('#layout #tab_0 .new_interval_form')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $this->waitForElement('[name=end_date]')->clear();
        $this->waitForElement('[name=end_date]')->value($value);
        $this->byCssSelector('#layout #tab_0 .new_interval_form')->click();

        if (isset($interval['min'])){
            $this->waitForElement('[name=min_los]')->clear();
            $this->waitForElement('[name=min_los]')->value($interval['min']);
        }

        if (isset($interval['max'])){
            $this->waitForElement('[name=max_los]')->clear();
            $this->waitForElement('[name=max_los]')->value($interval['max']);
        }

        $l = $this->execute(array('script' => "return window.$('#tab_0 .define_week_days td:not(._hide) input').length", 'args' => array()));
        for($i=0;$i<$l;$i++){
            $el = $this->byJQ("#layout #tab_0 .define_week_days td:not(._hide) input:eq(".$i.")");
            $el->clear();
            $el->value($interval['value_today']);
        }


        $this->byCssSelector('#layout #tab_0 .new_interval_form a.save_add_interval')->click();
        $this->save();
    }

    public function rates_remove_rate() {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        $this->waitForElement('#layout .nav-tabs .base.active a')->click();
        $this->waitForElement('#layout .tab-pane.base.active .intervals-table tr.r_rate:last .interval_delete', 15000, 'jQ')->click();
        $this->waitForElement('#confirm_delete', 50000, 'css');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();
        $this->save();
    }

    public function rate_addRate($interval, $type = false){
        $url = $this->_prepareUrl($this->roomRate_url);
        echo 'U: ' . $this->url() . PHP_EOL;
        if($this->url() != $url) {
            $this->url($url);
            $this->waitForLocation($url);
            $this->refresh();
        }

        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
        }
        //$this->waitForElement('.nav-tabs li.base:first a', 15000, 'jQ')->click();
        $add_new_rate_plan = $this->waitForElement('#layout .add_interval', 15000, 'jQ');

        $add_new_rate_plan->click();
        if(isset($interval['name']))
            $this->waitForElement('[name=interval_name]', 15000, 'jQ' )->value($interval['name']);
        $this->waitForElement('[name=start_date]', 15000, 'jQ')->click();
        $this->waitForElement('.new_interval_form', 15000, 'jQ')->click();
        $value = $this->convertDateToSiteFormat($interval['start']);
        $el = $this->waitForElement('[name=start_date]', 15000, 'jQ');
        // $el->clear();
        // $el->value($value);
        $this->execJS('$(".new_interval_form [name=start_date]").val("'.$value.'");');
        $this->waitForElement('[name=end_date]', 15000, 'jQ')->click();
        $this->waitForElement('.new_interval_form', 1500, 'jQ')->click();
        $value = $this->convertDateToSiteFormat($interval['end']);
        $el = $this->waitForElement('[name=end_date]', 15000, 'jQ');
        // $el->clear();
        // $el->value($value);
        $this->execJS('$(".new_interval_form [name=end_date]").val("'.$value.'");');
        $this->waitForElement('.new_interval_form', 1500, 'jQ')->click();

        if (isset($interval['min'])){
            $this->waitForElement('[name=min_los]', 15000, 'jQ')->value($interval['min']);
        }

        if (isset($interval['max'])){
            $this->waitForElement('[name=max_los]', 15000, 'jQ')->value($interval['max']);
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

    public function rate_copy_intervals($type = false){
        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
        } else {
            $this->waitForElement('.nav-tabs li.base:first a', 15000, 'jQ')->click();
        }
        $this->waitForElement('.copy_section.copy_rates', 15000, 'jQ')->click();
        $this->waitForElement('.modal-content', 15000, 'jQ');
        $this->waitForElement('.modal-content .save_changes3', 15000, 'jQ')->click();
        $this->waitForElement('.room_rate_copy_confirm_modal', 15000, 'jQ');
        $this->waitForElement('.room_rate_copy_confirm_modal .save_changes3', 15000, 'jQ')->click();
        $this->waitForElement('.r_rate', 15000, 'jQ');
        $this->save();
    }

    public function rate_delRate($type = false){
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));
        if ($type) {
            $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();
            $this->waitForElement('#layout .intervals-table tr.r_rate:last .interval_delete', 15000, 'jQ')->click();
        } else {
            $this->waitForElement('.nav-tabs li.base:first a', 15000, 'jQ')->click();
            $this->byJQ('#tab_0 .intervals-table tr.r_rate:last .interval_delete')->click();
        }
        $this->waitForElement('#confirm_delete', 50000, 'css');
        $this->byCssSelector('#confirm_delete .btn_delete')->click();
        $this->save();
    }

    public function rate_delAllRates($type) {
        $this->url($this->_prepareUrl($this->roomRate_url));
        $this->waitForLocation($this->_prepareUrl($this->roomRate_url));

        $this->waitForElement('.nav-tabs a:contains('.$type['name'].')', 15000, 'jQ')->click();

        $cnt = $this->execute(array('script' => 'return $(\'#layout .intervals-table:visible tr.r_rate .interval_delete\').length;', 'args' => array()));
        for($i = 0; $i < $cnt; $i++) {
            $this->waitForElement('#layout .intervals-table:visible tr.r_rate:eq(0) .interval_delete', 15000, 'jQ')->click();
            $this->waitForElement('#confirm_delete', 50000, 'css');
            $this->byCssSelector('#confirm_delete .btn_delete')->click();
        }

        if($cnt)
            $this->save();
    }

    public function roomtype_delRoomType($type){
        $this->url($this->_prepareUrl($this->roomType_url));
        $this->waitForLocation($this->_prepareUrl($this->roomType_url));

        if ($type) {
            $cnt = $this->execute(array(
                'script' => 'return $(\'.nav-tabs a:contains(' . $type['name'] . ')\').length;',
                'args' => array()
            ));
            for($i = 0; $i < $cnt; $i++) {
                $this->waitForElement('.nav-tabs a:contains(' . $type['name'] . ')', 15000, 'jQ')->click();
                $this->waitForElement('.nav-tabs li .remove-tab', 15000, 'jQ')->click();
                $this->waitForElement('#confirm_modal .btn_ok', 15000, 'jQ')->click();
            }
        }

        if(!$type || $cnt > 0)
            $this->save();
    }

    public function roomtype_addRoomType($roomtype){
        $this->url($this->_prepareUrl($this->roomType_url));
        $this->waitForLocation($this->_prepareUrl($this->roomType_url));
        $this->waitForElement('.add-room-type-btn', 15000, 'css')->click();
        $el = $this->waitForElement('[name^=\'room_type_title_langs\']', 15000, 'jQ');
        $el->click();
        $el->value($roomtype['name']);
        $el = $this->waitForElement('[name=\'room_type_capacity\']', 15000, 'jQ');
        $el->clear();
        $el->value($roomtype['rooms']);
        $el = $this->waitForElement('[name^=\'room_type_descr_langs\']', 15000, 'jQ');
        $el->clear();
        $el->value($roomtype['room_type_descr_langs']);
        $this->save();

        return $this->execute(array(
            'script' => "return $('#layout .roomtype_tabs .tab-pane.active [name=room_type_id]').val();",
            'args' => array()
        ));
    }
}
