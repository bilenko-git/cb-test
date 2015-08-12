<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

/**
 * should be hotel with Bank Transfer(ebanking) activated and available room for tomorrow
 */
class booking_to_cache extends test_restrict{
    private $testUrl = 'http://{server}/reservas/{property_id}';
    public function testSteps() {

        $this->setupInfo('', '', '', 366);

        $startDate = date('Y-m-d', strtotime('+1 day'));
        $endDate = date('Y-m-d', strtotime('+2 day'));
        
        $url = $this->_prepareUrl($this->testUrl).'#checkin='.$startDate.'&checkout='.$endDate;
        $this->url($url);
        $this->waitForLocation($url);
        
        //looking for first room block in list
        try {
            $el = $this->waitForElement('.room_types .room:first', 20000, 'jQ');
        }
        catch(\Exception $e) {
            $this->fail('No rooms to booking');
        }
        
        $roomTypeId = $this->getAttribute($el, 'data-room_type_id');
        $selectName = $this->execute(array('script' => 'return window.$(".room_types .room:first select.rooms_select").attr("name")', 'args' => array()));
        $rateId = preg_replace('/qty_rooms\[(\d+)\]/', '$1', $selectName);
        
        //get cache before booking
        $beforeAvailability = $this->getAvailability($startDate, $endDate, $roomTypeId, false, true);
        
        //select 1 room
        $el->byCssSelector('div.rooms_select button')->click();
        $this->byjQ('div.rooms_select ul.dropdown-menu li:eq(1) a')->click();
        
        //waiting for folio js execution
        $this->waitForElement('.selected_rooms_price');
        $this->byCssSelector('.book_now')->click();
        
        //waiting for rendering
        $el = $this->waitForElement('select[name="country"]');
        
        //fill out all inputs/textarea
        $this->execute(array('script' => 'window.$("input:text[class~=\'required\']").filter(function(){return !this.value;}).val(\'test\')', 'args' => array()));
        $this->execute(array('script' => 'window.$("textarea[class~=\'required\']").filter(function(){return !this.value;}).val(\'test\')', 'args' => array()));
        
        //set country
        $el->value('US');
        
        //set email
        $this->byId('email')->value('test@test.com');
        
        //select Bank Transfer
        $this->byCssSelector('.payment_method label[for="ebanking"]')->click();
        
        //Go booking
        $this->byCssSelector('.finalize')->click();
        
        //waiting for success status
        try {
            $this->waitForElement('.reserve_success', 20000);
        }
        catch (\Exception $e) {
            $this->fail('Reserva was not added');
        }
        
        //getting cache after booking
        $afterAvailability = $this->getAvailability($startDate, $endDate, $roomTypeId, false, true);
        
        //need to get base rate. Base rate is last in array (the first elements for associations)
        $checkIdx = count($afterAvailability['data'])-1;
        $availBefore = $beforeAvailability['data'][$checkIdx]['rates'][$rateId][$startDate]['avail'];
        $availAfter = $afterAvailability['data'][$checkIdx]['rates'][$rateId][$startDate]['avail'];
        
        echo "Before:$availBefore, After:$availAfter";
        
        $this->assertEquals($availBefore, $availAfter + 1);
    }
}
?>
