<?
    require_once 'BaseTest.php';

    class UploadTest extends BaseTest {
        protected $url = 'http://hotel.acessa.loc/connect/31';

        public function testUpload()
        {
            $this->loginToSite(array($this, 'step1'));
        }

        public function closeModals(){
            $modals = $this->webDriver->findElements(WebDriverBy::className('modal'));
            foreach($modals as $modal){
                if($modal->isDisplayed()){
                    $modal->findElement(WebDriverBy::cssSelector('.btn.blue'))->click();
                }
            }
        }

        public function step1(){
            $this->webDriver->get($this->url);
            $this->closeModals();
            $this->assertContains('Dashboard', $this->webDriver->findElement(WebDriverBy::cssSelector('.page-title'))->getText());
            $js_BET = $this->getJSObject('BET');
            print_r($js_BET);
        }
    }
?>