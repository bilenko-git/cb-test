<?php

trait Inventory {

    private $inventory_url = 'http://{server}/connect/{property_id}#/roomTypes';

    public function inventory_create_room_type($room_type, $clean_first) {
        if ($clean_first) {
            $this->inventory_delete_room_type($room_type);
        }

        $this->url($this->_prepareUrl($this->inventory_url));
        $this->waitForLocation($this->_prepareUrl($this->inventory_url));
        $this->waitForElement('.add-room-type-btn', 15000, 'css')->click();
        $el = $this->waitForElement('[name^=\'room_type_title_langs\']', 15000, 'jQ');
        $el->click();
        $el->value($room_type['name']);
        $el = $this->waitForElement('[name=\'room_type_capacity\']', 15000, 'jQ');
        $el->clear();
        $el->value($room_type['rooms']);
        $el = $this->waitForElement('[name^=\'room_type_descr_langs\']', 15000, 'jQ');
        $el->clear();
        $el->value($room_type['room_type_descr_langs']);
        $this->save();

        $room_type_id = $this->execute(array(
            'script' => "return $('#layout .roomtype_tabs .tab-pane.active [name=room_type_id]').val();",
            'args' => array()
        ));

        return array_merge($room_type, array('room_type_id' => $room_type_id));
    }

    public function inventory_delete_room_type($room_type) {
        $this->url($this->_prepareUrl($this->inventory_url));
        $this->waitForLocation($this->_prepareUrl($this->inventory_url));

        if ($room_type) {
            $cnt = $this->execute(array(
                'script' => 'return $(\'.nav-tabs a:contains(' . $room_type['name'] . ')\').length;',
                'args' => array()
            ));
            for($i = 0; $i < $cnt; $i++) {
                $this->waitForElement('.nav-tabs a:contains(' . $room_type['name'] . ')', 15000, 'jQ')->click();
                $this->waitForElement('.nav-tabs li .remove-tab', 15000, 'jQ')->click();
                $this->waitForElement('.modal-apply-modification .btn_ok', 15000, 'jQ')->click();
            }
        }

        if(!$room_type || $cnt > 0)
            $this->save();
    }
}
