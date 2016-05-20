<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class copy_intervals extends base_rates{
    private $invoice_url = 'http://{server}/connect/{property_id}#/setupInvoicing';
    private $roomtype = array(
        'name' => 'arrival_departure',
        'rooms' => 1,
        'room_type_descr_langs' =>'test'
    );
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
    public function testSteps(){

        $this->setupInfo('PMS_user');
        //$this->setupInfo('', '', '', 366, $br);
        $this->loginToSite();
        $this->addRoomType($this->roomtype);
        $this->addRate($this->interval);
        $this->copy_intervals($this->roomtype);

        $this->delRate();
        $this->delRoomType($this->roomtype);
    }

}
?>
