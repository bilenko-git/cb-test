<?php
    trait Waiters{
        /**
         * 
         * @param type $selector
         * @return type
         */
        public function byJQ($selector){
            $pos = strpos($selector, ':');

            if ($pos === false) {
                $element = $this->execute(array('script' => 'return window.$("' . $selector . ':visible").get(0)', 'args' => array()));
            } else {
                $element = $this->execute(array('script' => 'return window.$("' . $selector . '").get(0)', 'args' => array()));
            }
             return $element ? $this->elementFromResponseValue($element) : false;
        }

        /**
         * 
         * @param type $selector
         * @param type $timeout
         * @param type $selType (css|xpath|jQ)
         * @return type
         */
        public function waitForElement($selector, $timeout = 15000, $selType='css', $check_displayed = true){
            $element = null;
            if (getenv('SELENIUM_LOCAL')) {
                //It seems the click event handler is not assigned yet in some cases, so we need the delay.
                sleep(1);
            }
            echo 'Searching element: '. $selector.PHP_EOL;
            try {
                $this->waitUntil(function($testCase) use ($selector, $selType, &$element, $check_displayed) {
                    try {
                        switch ($selType) {
                            case 'css':
                                $element = $testCase->byCssSelector($selector);
                                break;
                            case 'xpath':
                                $element = $testCase->byXPath($selector);
                                break;
                            case 'jQ':
                                $element = $this->byJQ($selector);
                                break;

                            default:
                                $testCase->fail('Unknown selector type');
                        }

                        $boolean = $element?($check_displayed ? $element->displayed() : true):false;

                    } catch (\Exception $e) {
                        $boolean = false;
                    }
                    /*if ($boolean !== true)
                        usleep(500000);*/
                    return $boolean === true ?: null;
                }, $timeout);
            } catch (\Exception $e) {
                $element = null;
            }
            /*if (getenv('SELENIUM_LOCAL')) {
                sleep(1);
            }*/
            echo 'return element: '. ($element?'Object':'false').PHP_EOL;
            return $element;
        }
        
        public function waitForLocation($url, $timeout = 50000){
            if (getenv('SELENIUM_LOCAL')) {
                //It seems the click event handler is not assigned yet in some cases, so we need the delay.
                sleep(1);
            }
            echo "need:".$url."\n";
            $this->waitUntil(function($testCase) use ($url) {
                echo "have:".$testCase->url()."\n";
                $pos = strpos($testCase->url(), 'reservas');
                if ($pos !== false && strpos($url, 'checkin') === false){
                    $hash = strpos($testCase->url(), '#');
                    $newurl = substr($testCase->url(), 0, $hash);
                    echo $hash;
                    echo 'new-'.$newurl;
                } else {
                    $newurl = $testCase->url();
                }
                $bUrl = $newurl == $url;
                return $bUrl ?: null;
            }, $timeout);
        }

        public function waitToVisible($element, $timeout = 5000){
            $this->waitUntil(function() use($element){
                $displayed = $element->displayed();
                /*if (!$displayed)
                    usleep(500000);*/
                return $displayed ?: null;
            }, $timeout);
        }
        
        public function waitUntilVisible($element, $timeout = 10000){
            if(!$element)
                return;
            $this->waitUntil(function() use($element){
                $displayed = $element->displayed();
                /*if ($displayed)
                    usleep(500000);*/
                return $displayed ? null: true;
            }, $timeout);
        }
        
        public function betLoaderWaiting() {
            //loading waiting
            $this->waitUntil(function($test) {
                $res = $test->execute(array('script' => "return window.$('.layout-loading:visible').length", 'args' => array()));
                return $res==0 ? true : null;
            },20000);
        }
    }
?>