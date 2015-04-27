<?
    /*
     * Base Test that provides login at site check login
     * add exception and error handlers
     * and allow to take screenshoots on error or exception
     * */

    class BaseTest extends PHPUnit_Framework_TestCase {

        /**
         * @var \RemoteWebDriver
         */
        protected $webDriver;

        public function setUp()
        {
            set_error_handler(array($this, '_error_handler'));
            set_exception_handler(array($this, '_exception_handler'));
            $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'firefox');
            $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
        }

        protected $login_url = 'http://hotel.acessa.loc/auth/logout';
        protected $login = 'engineering@cloudbeds.com';
        protected $password = 'cl0udb3ds';

        public function loginToSite(callable $callback = null){
            $this->webDriver->get($this->login_url);//load url

            /*login to site*/
            $this->webDriver->findElement(WebDriverBy::id('email'))->sendKeys($this->login);
            $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys($this->password);
            $this->webDriver->findElement(WebDriverBy::xpath("//div[@class='form-actions']//button[normalize-space(.)='Login']"))->click();

            $this->webDriver->wait(2, 30)->until(array($this, 'checkLoggedIn'));

            if($callback) call_user_func($callback);
        }

        public function checkLoggedIn(){
            return $this->webDriver->getCurrentURL() !== $this->login_url;
        }

        public function takeScreenShoot($filename){
            $dir = __DIR__ . '/screenshoots/' . basename(__FILE__, ".php") . '/';
            if(!file_exists($dir)) @mkdir($dir, 0777, true);
            $this->webDriver->takeScreenshot( $dir . $filename );
        }

        public function getJSObject($name = ''){
            if($name){
                $this->webDriver->wait(30, 2000)->until(
                    function ($driver) use ($name) {
                        return !$driver->executeScript('return '.$name);
                    }
                );
            }

            return false;
        }

        public function _exception_handler(Exception $e){
            $this->takeScreenShoot($e->getMessage() . '_' . time() . '.png');
        }
        public function _error_handler($e){
            $this->takeScreenShoot($e . '_' . time() . '.png');
        }
        public function __destruct(){
            $this->takeScreenShoot('finish_' . time() . '.png');
            $this->webDriver->close();
        }
    }
?>