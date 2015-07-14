<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

class booking_to_cache extends test_restrict{
    private $testUrl = 'http://{server}/reservas/{property_id}';
    public function testSteps() {
        $this->setupInfo('', '', '', 31);
        $url = $this->_prepareUrl($this->testUrl).'#checkin='.date('Y-m-d').'&checkout='.date('Y-m-d', strtotime('+1 day'));
        $this->url($url);
        $this->waitForLocation($url);
        
        $el = $this->waitForElement('.room_types .room:first', 20000, 'jQ');
        
        if(!$el)
            $this->fail('No rooms to booking');

        $roomTypeId = $this->getAttribute($el, 'data-room_type_id');
        
        echo $roomTypeId;
        
        $el->byCssSelector('.rooms_select button')->click();
        $this->byjQ('.rooms_select ul.dropdown-menu li:eq(1) a')->click();
        
        $this->waitForElement('.selected_rooms_price');
        $this->byCssSelector('.book_now')->click();
        
        $el = $this->waitForElement('select[name="country"]');
        
        $this->execute(array('script' => 'window.$("input:text[class~=\'required\']").filter(function(){return !this.value;}).val(\'test\')', 'args' => array()));
        $this->execute(array('script' => 'window.$("textarea[class~=\'required\']").filter(function(){return !this.value;}).val(\'test\')', 'args' => array()));
        
        $el->value('US');
        $this->byId('email')->value('test@test.com');
        
        $this->byCssSelector('.payment_method label[for="ebanking"]')->click();
        
        $this->byCssSelector('.finalize')->click();
        
    }
}
?>
