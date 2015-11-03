<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class fUpdate_rate extends base_rates{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_id}';
    private $availability_url = 'http://{server}/connect/{property_id}#/availability';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99',
        'start' =>'now',
        'end' =>'+3 days',
        'edit_end_day' =>'+10 days'
    );
    public function testSteps(){
        $step = $this;

        //$this->setupInfo('', '', '', 366);
        $this->setupInfo('PMS_user');
        $this->loginToSite();
        $this->addRate($this->interval);

        $room_type_id = $this->execute(array('script' => "return window.$('#tab_0 [name=room_type_id]').val()", 'args' => array()));
        $room_type = $this->execute(array('script' => "return window.TAFFY(BET.DB().select('room_types')[0])({room_type_id: String(".$room_type_id.")}).get()[0]", 'args' => array()));
        $rate_id = $this->execute(array('script' => "return window.$('#tab_0 [name=rate_id]').val()", 'args' => array()));

        $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);

        $this->interval['end'] =  $this->interval['edit_end_day'];
        $this->updateRate($this->interval);
        $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);

        $this->updateRate($this->interval, true);
        $this->avalCheck($this->interval,$room_type_id, $rate_id, $room_type);

        $this->delRate();

    }

}
?>
