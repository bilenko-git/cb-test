<?php
require_once('availability_matrix.php');

class availability_matrix_intervals_editor extends \MyProject\Tests\availability_matrix {
    private $intervals = array(
        'one_date_range' => array(
            'room_types' => '*:0:1',
            'date_range' => array(
                array(
                    'start_date' => '+1 days',
                    'end_date' => '+10 days',
                    'days_of_week' => array(
                        1 => false,
                        3 => false
                    )
                )
            ),
            'set_parameters' => array(
                /*'available' => 2,
                'rate' => 5,
                'MinLOS' => 1,
                'MaxLOS' => 2,*/
                'ClosedArrival' => true,
                'ClosedDeparture' => true,
                'closed' => true
            )
        ),
        'two_date_range_not_intersect' => array(),
        'two_date_range_intersect' => array()
    );

    /**
     * @param $expr *[1] - room type_id, *[2] - package_id, *[3] - count of room types/packages
     * @return array
     */
    public function prepareRoomTypes($expr = '*:*:*') {
        $items = $this->getRoomTypes(false);
        list($rmt_id, $pck_id, $cnt) = explode(':', $expr);

        $room_types = array();
        foreach($items as $item) {
            if($rmt_id != '*' && $item['room_type_id'] != $rmt_id) continue;
            if($pck_id != '*' && $item['package_id'] != $pck_id) continue;
            $room_types[] = $item;
        }

        if($cnt == '*' || $cnt <= count($room_types)) return $room_types;

        $rand = array_rand($room_types, $cnt);
        $result = array();

        foreach($rand as $rnd){
            $result[] = $room_types[$rnd];
        }

        return $result;
    }

    public function prepareDateRanges($expr = array()) {
        $result = array();
        foreach($expr as $i => $rng) {
            $one = array();
            print_r($rng);
            foreach($rng as $key => $value) {
                if(is_array($value)) {
                    foreach($value as $j => $v) {
                        $one[$key . '[' . $i . '][' . $j . ']'] = $v;
                    }
                } else {
                    $one[$key . '[' . $i . ']'] = $this->convertDateToSiteFormat($value);
                }
            }

            $result[] = $one;
        }

        return $result;
    }

    public function createInterval($data) {
        $long_interval_btn = $this->waitForElement('#layout .add_interval', 15000, 'jQ');
        $long_interval_btn->click();

        $form = $this->waitForElement('.availability-interval-editor');

        $room_types = $this->prepareRoomTypes($data['room_types']);
        $date_ranges = $this->prepareDateRanges($data['date_range']);
        $set_params = $data['set_parameters'];

        $this->selectPickerValue('room_types', array_map(function($a){return $a['package_id'] . '-' . $a['room_type_id'];}, $room_types));
        $this->selectPickerValue('set_parameters', array_keys($set_params));

        $add_range_btn = $this->waitForElement('.availability-interval-editor .add-range-btn', 15000, 'jQ');
        foreach($date_ranges as $ind => $dt_range) {
            foreach($dt_range as $k => $v) {
                $rmt_input = $this->waitForElement('.availability-interval-editor [name=\'' . $k .'\']', 15000, 'jQ', false);
                if($rmt_input->attribute('type') != 'checkbox') {
                    $rmt_input->click();
                    $this->fillDate($k, $v);
                    $form->click();//to lose focus from datepickers
                } else {
                    $this->execute(array(
                        'script' => '$(arguments[0]).prop("checked", arguments[1]);',
                        'args' => array(
                            $rmt_input->toWebDriverObject(),
                            $v ? 1 : 0
                        )
                    ));
                }
            }

            if($ind < count($date_ranges) -1) {
                $add_range_btn->click();
            }
        }

        foreach($set_params as $k => $v) {
            $param = $this->waitForElement('[data-param-name="' . $k . '"]', 15000, 'css', false);
            if($param->attribute('type') != 'checkbox') {
                $param->value($v);
            } else {
                $this->execute(array(
                    'script' => '$(arguments[0]).bootstrapSwitch(\'toggleState\', arguments[1]);',
                    'args' => array(
                        $param->toWebDriverObject(),
                        !$v ? 1 : 0 //here is reverse order because params is opened, openedArrival, openedDeparture, but we use closed, ClosedArrival ClosedDeprature
                    )
                ));
            }
        }

        $form->click();
        $add_interval_btn = $this->waitForElement('a[type=\'submit\']', 15000, 'jQ');
        $add_interval_btn->click();
    }

    public function getBaseRates(){}
    public function checkIntervalCache(){}
    public function checkIntervalBaseRates(){}

    public function test_one_interval() {
        $this->prepareTest();
        $this->createInterval($this->intervals['one_date_range']);
        $this->saveAvailability();
    }

/*
    public function test_two_intervals_ni(){}
    public function test_two_intervals_i(){}

    public function test_add_interval_with_calendar_custom_price(){}
    public function test_base_rate_applying(){}
*/
}