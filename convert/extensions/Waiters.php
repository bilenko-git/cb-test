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
             return $this->elementFromResponseValue($element);
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
            echo 'Searching element: '. $selector.PHP_EOL;
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
                if ($boolean !== true)
                    usleep(500000);
                return $boolean === true ?: null;
            }, $timeout);
            if (getenv('SELENIUM_LOCAL')) {
                sleep(1);
            }
            echo 'return element: '. ($element?'Object':'false').PHP_EOL;
            return $element;
        }
        
        public function waitForLocation($url, $timeout = 5000){
            $this->waitUntil(function($testCase) use ($url, $timeout) {
                $bUrl = $testCase->getBrowserUrl() == $url;
                if (!$bUrl)
                    usleep(500000);
                if (getenv('SELENIUM_LOCAL')) {
                    sleep(1);
                }
                return $bUrl;
            }, $timeout);
        }

        public function waitUntilVisible($element, $timeout = 1000){
            $this->waitUntil(function() use($element){
                $displayed = $element->displayed();
                if (!$displayed)
                    usleep(500000);
                return $displayed;
            }, $timeout);
        }
    }
?>