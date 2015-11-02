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
        'start' => '+10 days',
        'end' => '+15 days'
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
        'start' => '+15 days',
        'end' => '+30 days'
    );
    private $roomtype = array(
        'name' => 'arrival_departure',
        'rooms' => '10',
        'room_type_descr_langs' =>'test'
    );
    public function testSteps(){
        $step = $this;

        $this->loginToSite();

       // $this->addRoomtype($this->roomtype);
        $this->addRate($this->interval, $this->roomtype);
        $room_type_id = $this->execute(array('script' => "return window.$('[name=room_type_id]:visible').val()", 'args' => array()));
        $room_type = $this->execute(array('script' => "return window.TAFFY(BET.DB().select('room_types')[0])({room_type_id: String(".$room_type_id.")}).get()[0]", 'args' => array()));
        $rate_id = $this->execute(array('script' => "return window.$('[name=rate_id]:visible').val()", 'args' => array()));



        $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);

        $this->interval['end'] =  $this->interval['edit_end_day'];
        $this->interval['max'] =  '5';

        $this->updateRate($this->interval, true);
        $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);

        $this->delRate();

        $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);
    }

}
?>
