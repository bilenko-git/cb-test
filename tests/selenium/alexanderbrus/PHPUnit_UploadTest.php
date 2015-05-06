<?
    require_once 'PHPUnit_BaseTest.php';

    class PHPUnit_UploadTest extends PHPUnit_BaseTest {
        protected $url = 'http://hotel.acessa.loc/connect/1';
        protected $uploadFiles = array(
            '/home/alex/skype2.jpg',
            //'/home/alex/photoshop.png'
        );

        public function testUpload()
        {
            $this->loginToSite(array($this, 'step1'));
        }

        public function step1(){
            $this->url($this->url);
            $this->closeModals();

            $this->checkProgileHotel();
            $this->checkRegCards();
        }

        public function checkProgileHotel(){
            $path = 'profilehotel';
            $this->assertTrue($this->goToLocation($path), 'Go to location ' . $path . ' - FAILED');
            $this->uploadHotelPhoto();
        }

        public function checkRegCards(){
            $path = 'registrationcards';
            $this->assertTrue($this->goToLocation($path), 'Go to location ' . $path . ' - FAILED');
            $this->uploadRegCardsPhoto();
        }

        public function uploadRegCardsPhoto(){
            $add_img_button = $this->element($this->using('css selector')->value('.btn.blue.add_header'));
            $add_img_button->click();

            $upload_button = $this->waitForElement('.fileinput-button.image_add_new');
            $upload_button->click();

            $modals = $this->findModals(true);
            if(!empty($modals)) {
                $modal = reset($modals);
                $this->uploadFileToElement('body>input[type=file]', reset($this->uploadFiles));

                $btns = $modal->elements($this->using('css selector')->value('.btn.done'));
                foreach ($btns as $btn)
                    $btn->click();//click Done

                $btns = $modal->elements($this->using('css selector')->value('.btn.save-uploader'));
                foreach ($btns as $btn)
                    $btn->click();//click Save & Continue

                $btn = $this->waitForElement('.btn.blue.image_save', 5000, true);
                if ($btn) $btn->click();//click Save

                $property_id = $this->getJSObject('BET.config.property_id');
                $uiIds = $this->getUIPhotoIds(array(
                    'css selector' => '.imgs-registrationCards',
                    'attribute' => 'data-id',
                    'visible' => true
                ));
                $this->assertFileColumnsInDB($uiIds, $property_id, 'hotel', 'registrationCards');
            }
        }

        public function uploadHotelPhoto(){
            $upload_button = $this->element($this->using('css selector')->value('.multiple_upload .qq-upload-button'));
            $upload_button->click();
            $modals = $this->findModals(true);
            if(!empty($modals)) {
                $modal = reset($modals);
                $this->uploadFileToElement('body>input[type=file]', $this->uploadFiles);

                $btns = $modal->elements($this->using('css selector')->value('.btn.done'));
                foreach($btns as $btn)
                    $btn->click();//click Done

                $btns = $modal->elements($this->using('css selector')->value('.btn.save-uploader'));
                foreach($btns as $btn)
                    $btn->click();//click Save & Continue

                $btn = $this->waitForElement('.btn.saveButton', 5000, true);
                if($btn) $btn->click();//click Save

                /*assert [data-qe-iq] to saved*/
                $save_result = '';
                $this->waitUntil(function() use (&$save_result){
                    if($save_element = $this->byCssSelector('[data-qe-id]')) {
                        $save_result = $save_element->attribute('data-qe-id');
                        echo "current result is: " . $save_result . "\n";
                        return $save_result == 'saved'?true:null;
                    }

                    return null;
                }, 5000);//5000ms until saving process finish

                $this->assertEquals('saved', $save_result, 'Saving result is: ' . $save_result); //assert saving result
                /*./assert [data-qe-id] to saved*/

                $property_id = $this->getJSObject('BET.config.property_id');

                $uiIds = $this->getUIPhotoIds(array(
                    'css selector' => '.imgs-profile.additional_photos',
                    'attribute' => 'data-id'
                ));
                $this->assertFileColumnsInDB($uiIds, $property_id, 'hotel', 'hotelProfile');
                //$this->assertFileExistsInDB($uiIds);
            }
        }

        public function getUIPhotoIds($params = array()){
            $options = array(
                'css selector' => '.imgs-profile.additional_photos',
                'attribute' => 'data-id'
            );//defaults to hotelProfile images
            $options = array_merge($options, $params);
            $elements = $this->elements($this->using('css selector')->value($options['css selector']));
            $result = array();
            foreach($elements as $element){
                if($element instanceof PHPUnit_Extensions_Selenium2TestCase_Element){
                    if(isset($options['visible']) && $options['visible'] == $element->displayed()) {
                        $result[] = $element->attribute($options['attribute']);
                    }
                }
            }

            return $result;
        }


    }
?>