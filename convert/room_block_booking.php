<?php
namespace MyProject\Tests;
require_once 'test_restrict.php';

/**
 * Test restrictions:
 * - hotel shold have available room on next week
 * - there should be no room blocks for next week, otherwise test will not start
 * - basic availability should be activated(CRM)
 * 
 */
class room_block_booking extends test_restrict{
    private $roomBlockUrl = 'http://{server}/connect/{property_id}#/roomblocks';
    private $bookingUrl = 'http://{server}/reservas/{property_id}';
    
    public function testSteps() {
        $newBlockingId = false;
        $this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 33);

        //will check next week
        $startDate = date('Y-m-d', strtotime('next monday'));
        $endDate = date('Y-m-d', strtotime('+7 day', strtotime($startDate)));
        //lets set block on next Wednesday
        $blockDate = date('Y-m-d', strtotime('+2 day', strtotime($startDate)));
        
        //get first available room on booking
            $url = $this->_prepareUrl($this->bookingUrl).'#checkin='.$startDate.'&checkout='.$endDate;
            $this->url($url);
            $this->waitForLocation($url);

            //looking for first room block in list
            try {
                $el = $this->waitForElement('.room_types .room:first', 20000, 'jQ');
            }
            catch (\Exception $e)
            {
                $this->fail('No rooms to booking');
            }

            $roomTypeId = $this->getAttribute($el, 'data-room_type_id');
            $selectName = $this->execute(array('script' => 'return window.$(".room_types .room:first select.rooms_select").attr("name")', 'args' => array()));
            $rateId = preg_replace('/qty_rooms\[(\d+)\]/', '$1', $selectName);

            //get cache before booking
            //$startAvailability = $this->getAvailability($startDate, $endDate, $roomTypeId, false, true);
            //$checkIdx = count($checkAvailability['data'])-1;
            //$availBefore = $startAvailability['data'][$checkIdx]['rates'][$rateId][$blockDate]['avail'];
        //
            
        //Adding room block
            $this->loginToSite();
            //going to RoomBlock page
            $url = $this->_prepareUrl($this->roomBlockUrl);
            $this->url($url);
            $this->waitForLocation($url);
            
            try {
                $el = $this->waitForElement('#layout a.add_interval');
            }
            catch (\Exception $e)
            {
                $this->fail('Cannon navigate Room Block URL');
            }
            
            $startBlockedIds = $this->_getCurrentBlocksIds();
            
            $el->click();
            
            $el = $this->waitForElement('#layout input[name="start"]');
            $el->clear();
            $el->value($this->convertDateToSiteFormat($blockDate));
            
            $el = $this->byCssSelector('#layout input[name="end"]');
            $el->clear();
            $el->value($this->convertDateToSiteFormat($blockDate));
            
            //now selecting our test room
            $this->byCssSelector('#layout #period_roomTypes + div.ms-parent > button')->click();
            $el = $this->waitForElement('input[value="'.$roomTypeId.'"] + label')->click();
            
            //submit
            $el = $this->byCssSelector('#layout a[type="submit"]')->click();
            
            try {
                $el = $this->waitForElement('#panel-save .btn-save');
            }
            catch (\Exception $e)
            {
                $this->fail('Creating RoomBlock. Save btn was not appear');
            }
            
            //Save
            $el->click();
            
            //Waiting for save done
            try {
                $this->waitForElement('.toast-bottom-left', 50000, 'css');
            }
            catch(\Exception $e)
            {
                $this->fail('Creating RoomBlock. Saving problem.');
            }
            
            $currentBlockedIds = $this->_getCurrentBlocksIds();
            $newBlocking = array_diff($currentBlockedIds, $startBlockedIds);
            if($newBlocking)
                $newBlockingId = $newBlocking[array_keys($newBlocking)[0]];
        //
            echo "New BlockingRowId = ".$newBlockingId;
        
        //getting new cache
            $checkAvailability = $this->getAvailability($startDate, $endDate, $roomTypeId, false, true);
        //
        
        //removing our just created blocker
            if($newBlockingId) {
                $remove = $this->execJS('
                    var $selTestEls = $(\'#layout #roomblocks_table tbody tr\');
                    var $el = null;
                    $selTestEls.each(function(){
                        if($(this).data(\'id\') == \''.$newBlockingId.'\'){
                           $el = $(this).find(\'a.interval_delete\');
                           return false;
                        }
                    });
                    return $el?$el[0]:null;
                ');

                if($remove) {
                    $remove = $this->elementFromResponseValue($remove);
                    $remove->click();

                    $this->waitForElement('#confirm_delete');
                    $this->waitForElement('.btn_delete')->click();
                    
                    try {
                        $el = $this->waitForElement('#panel-save .btn-save');
                    }
                    catch (\Exception $e)
                    {
                        $this->fail('Deleting RoomBlock. Save btn was not appear');
                    }

                    //Save
                    $el->click();

                    //Waiting for save done
                    try {
                        $this->waitForElement('.toast-bottom-left', 50000, 'css');
                    }
                    catch(\Exception $e)
                    {
                        $this->fail('Deleting RoomBlock. Saving problem.');
                    }
                }
            }
        //
            
        //checking if cache ok
            $checkIdx = count($checkAvailability['data'])-1;
            $availCheck = $checkAvailability['data'][$checkIdx]['rates'][$rateId][$blockDate]['avail'];

            $this->assertEquals($availCheck, 0);
        //
    }
    
    private function _getCurrentBlocksIds() {
        $ids = $this->execJS(
            'selTestIds=[];
            var $selTestEls = $(\'#layout #roomblocks_table tbody tr\');
            $selTestEls.each(function(){
                $id = $(this).data(\'id\');
                if($id)
                    selTestIds.push($id);
            });
            return selTestIds');
        
        return $ids;
    }
}