<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class Closed_arrival_departure extends base_rates{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';
    private $interval = array(
        'name' => 'interval',
        'value_today' => '99',
        'start' => '+11 days',
        'end' => '+15 days',
        'arrival' => false,
        'departure' => false
        //'min' => '2',
        //'edit_end_day' => '+12 days'
    );
    private $interval_before = array(
        'name' => 'interval before',
        'value_today' => '99',
        'start' => 'now',
        'end' => '+10 days'
    );
    private $interval_after = array(
        'name' => 'interval after',
        'value_today' => '99',
        'start' => '+16 days',
        'end' => '+30 days',
    );
    private $roomtype = array(
        'name' => 'arrival_departure',
        'rooms' => '10',
        'room_type_descr_langs' =>'test'
    );
    private $date_before = array(
        'start' => '+1 days',
        'end' => '+13 days',
    );
    private $date_after = array(
        'start' => '+13 days',
        'end' => '+25 days',
    );

    public function testSteps(){
        $step = $this;

        $this->setupInfo('PMS_user');
        $this->loginToSite();

        $this->addRoomType($this->roomtype);

        $this->addRate($this->interval, $this->roomtype);
        $room_type_id = $this->execute(array('script' => "return window.$('.tab-content:visible [name=room_type_id]').val()", 'args' => array()));
        $room_type = $this->execute(array('script' => "return window.TAFFY(BET.DB().select('room_types')[0])({room_type_id: String(".$room_type_id.")}).get()[0]", 'args' => array()));
        $rate_id = $this->execute(array('script' => "return window.$('.tab-content:visible [name=rate_id]').val()", 'args' => array()));
        $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);

        $update_interval = $this->interval;
        $update_interval['arrival'] = true;
        $this->updateRate($update_interval, true, $this->roomtype);
        $this->avalCheck($update_interval,$room_type_id, $rate_id, $room_type);


        $this->addRate($this->interval_before, $this->roomtype);
        $this->avalCheck($this->date_before,$room_type_id, $rate_id, $room_type);


        $update_interval = $this->interval;
        $update_interval['arrival'] = false;
        $update_interval['departure'] = true;
        $this->updateRate($update_interval, true, $this->roomtype);
        $this->avalCheck($update_interval,$room_type_id, $rate_id, $room_type);


        $this->addRate($this->interval_after, $this->roomtype);
        $this->avalCheck($this->date_after,$room_type_id, $rate_id, $room_type);

        $this->delRate($this->roomtype);
        $this->delRate($this->roomtype);
        $this->delRate($this->roomtype);

        $this->delRoomType($this->roomtype);

       // $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);
    }

}
?>
