<?php
namespace MyProject\Tests;
require_once 'base_rates.php';

class add_interval_for_ma extends base_rates{
    private $roomRate_url = 'http://{server}/connect/{property_id}#/roomRates';
    private $reservas_url = 'http://{server}/reservas/{property_reserva_code}';
    private $calendar_url = 'http://{server}/connect/{property_id}#/calendar';
    private $interval = array(
        'name' => 'interval today',
        'value_today' => '99',
        'start' => 'now',
        'end' => '+7 days',
        'min' => '2',
        'max' => '4',
        'arrival' => true,
        'departure' => true

    );
    private $price = 778;

    public function testSteps(){
        $test = $this;
        $this->setupInfo('PMS_user');
        $this->loginToSite();

        $this->addRate($this->interval);

         $room_type_id = $this->execute(array('script' => "return window.$('#tab_0 [name=room_type_id]').val()", 'args' => array()));
     //    $room_type = $this->execute(array('script' => "return window.TAFFY(BET.DB().select('room_types')[0])({room_type_id: String(".$room_type_id.")}).get()[0]", 'args' => array()));
         $rate_id = $this->execute(array('script' => "return window.$('#tab_0 [name=rate_id]').val()", 'args' => array()));
         $interval =$this->interval;

         $arr = $this->getAvailability($this->convertDateToSiteFormat($interval['start'],'Y-m-d'),$this->convertDateToSiteFormat($interval['end'],'Y-m-d'),$room_type_id);
       //  $availability = $arr->data[0]->rates->{$rate_id}->{$this->convertDateToSiteFormat($interval['start'],'Y-m-d')}->avail;

        $rate_obj = $arr->data[0]->rates->{$rate_id};

        $this->loginToMA();
        $el = $this->waitForElement('.menu-item-availability a', 15000, 'css');
        $el->click();
        $this->waitForElement('.availTableWrapper', 15000, 'css');
        $i=2;
        foreach($rate_obj as $key => $el) {
            print_r("+++++++++++++++++");
            $avail = $this->waitForElement('/html/body/div[2]/div/div/div/div[8]/div[3]/table/tbody/tr[1]/td['.$i.']/input[1]', 15000, 'xpath');
            $test->assertEquals($avail->value(), $el->avail);
            $rate = $this->waitForElement('/html/body/div[2]/div/div/div/div[8]/div[3]/table/tbody/tr[1]/td['.$i.']/input[2]', 15000, 'xpath');
            $test->assertEquals($rate->value(), $el->rate);
            $min = $this->waitForElement('/html/body/div[2]/div/div/div/div[8]/div[3]/table/tbody/tr[1]/td['.$i.']/input[3]', 15000, 'xpath');
            $test->assertEquals($min->value(), $el->min_los);
            $max = $this->waitForElement('/html/body/div[2]/div/div/div/div[8]/div[3]/table/tbody/tr[1]/td['.$i.']/input[4]', 15000, 'xpath');
            $test->assertEquals($max->value(), $el->max_los);
            $arrival = $this->waitForElement('/html/body/div[2]/div/div/div/div[8]/div[3]/table/tbody/tr[1]/td[2]/div[2]/input', 15000, 'xpath');
            $test->assertEquals($this->isChecked($arrival), 1);
            $depart = $this->waitForElement('/html/body/div[2]/div/div/div/div[8]/div[3]/table/tbody/tr[1]/td[2]/div[3]/input', 15000, 'xpath');
            $test->assertEquals($this->isChecked($depart), 1);
            print_r("+++++++++++++++++");
            $i++;
        }

         $this->delRate();
    }

}
?>
