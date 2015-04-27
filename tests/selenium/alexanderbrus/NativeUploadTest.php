<?
    require_once 'NativeBaseTest.php';

    class NativeUploadTest extends NativeBaseTest {
        protected $url = 'http://hotel.acessa.loc/connect/31';
        protected $uploadFiles = array(
            '/home/alex/skype2.jpg',
            '/home/alex/123456.png',
            '/home/alex/photoshop.png'
        );

        public function testUpload()
        {
            $this->loginToSite(array($this, 'step1'));
        }

        public function step1(){
            $path = 'profilehotel';
            $this->url($this->url);
            $this->closeModals();

            $this->assertTrue($this->goToLocation($path), 'Go to location ' . $path . ' - FAIL');
            $this->uploadHotelPhotoToModal();
        }

        public function uploadFileToElement($css_selector, $files){
            foreach($files as $file){
                $input_file = end($this->elements($this->using('css selector')->value($css_selector)));
                if($input_file instanceof PHPUnit_Extensions_Selenium2TestCase_Element) {
                    $this->setAttribute($input_file, 'style', 'display: block!important;');
                    $input_file->value($file);
                }
            }
        }

        public function uploadHotelPhotoToModal(){
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

                $btn = $this->element($this->using('css selector')->value('.btn.saveButton'));
                $btn->click();//click Save

                $save_result = '';
                $this->waitUntil(function() use (&$save_result){
                    if($save_element = $this->byCssSelector('[data-qe-id]')) {
                        $save_result = $save_element->attribute('data-qe-id');
                        echo "current result is: " . $save_result . "\n";
                        return $save_result == 'saved'?true:null;
                    }

                    return null;
                }, 3000);

                $this->assertEquals('saved', $save_result, 'Saving result is: ' . $save_result); //assert saving result
            }
        }

        public function getBETJSErrors(){
            $result = $this->execute(array('script' => 'return BET.onerror.all();', 'args' => array()));
            print_r($result);
        }
    }
?>