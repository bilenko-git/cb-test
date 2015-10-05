<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class booking extends test_restrict{
    private $reservas_url = 'http://{server}/reservas/{property_id}';

     public function createReservation($start, $end)
     {
         $this->url($this->_prepareUrl($this->reservas_url));
         $this->waitForLocation($this->_prepareUrl($this->reservas_url));

         $form = $this->waitForElement('.search_panel', 20000, 'css');
         $start_date = $this->convertDateToSiteFormat($start);
         $this->byName('search_start_date')->clear();
         $this->byName('search_start_date')->value($start_date);
         $form->click();

         $end_date = $this->convertDateToSiteFormat($end);
         $this->byName('search_end_date')->clear();
         $this->byName('search_end_date')->value($end_date);
         $this->byName('end_date')->click();
         $form->click();

         $days = intval((strtotime($this->convertDateToSiteFormat($end,'Y-m-d')) - strtotime($this->convertDateToSiteFormat($start,'Y-m-d'))) / 86400);
         echo 'Search start date = ' . $start_date . PHP_EOL;
         echo 'Search end date = ' . $end_date . PHP_EOL;
         echo 'Days = ' . $days . PHP_EOL;

         //////////////////////////////////////


         $this->byName('check_availability')->click();

        $this->waitForElement('.available_rooms', 15000, 'css');
        // TODO-natali check rooms and click book_now btn
        $booking_types = $this->execute(array('script' => "return window.$('.available_rooms .room_types [data-room_type_id]')", 'args' => array()));
         echo 'Room types = ' . count($booking_types) . PHP_EOL;

         if (count($booking_types)) {
             $booking_types_num = $this->execute(array('script' => "return window.$('.available_rooms .room_types [data-room_type_id]').length", 'args' => array()));
             var_dump($booking_types);
             $this->waitForElement('.available_rooms .room_types [data-room_type_id]:last .rooms_select .dropdown-toggle', 15000, 'jQ')->click();
         }
        //$booking_room = $this->execute(array('script' => "return window.$('.available_rooms .room_types [data-room_type_id] .roomtype select.rooms_select option').length", 'args' => array()));
         echo '~~~~~~~~~~~~~~~~ Booking Creation successfully ~~~~~~~~~'.PHP_EOL;
         echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'.PHP_EOL;
    }
}
?>
