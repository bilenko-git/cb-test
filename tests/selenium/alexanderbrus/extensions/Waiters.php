<?php
    trait Waiters{
        /*public function waitForElement($selector, $timeout = 5000, $assert = false, $message = ''){
            $element = false;
            $this->waitUntil(function() use($selector, &$element){
                $elements = $this->elements($this->using('css selector')->value($selector));
                foreach($elements as $el){
                    if($el->displayed()){
                        $element = $el;
                        break;
                    }
                }
                return ($element)?true:false;
            }, $timeout);

            if(!$element && $assert){
                if(!$message) $message = 'Element '. $selector . ' not found.';
                $this->fail($message);
            }

            return $element;
        }*/

        /*
         * Working ok
         */
        public function byJQ($selector){
            $element = $this->execute(array('script' => 'return window.$("' . $selector . '").get(0)', 'args' => array()));
            return $this->elementFromResponseValue($element);
        }

        public function waitForElement($selector, $timeout = 5000, $selType='css'){
            $element = null;
            $this->waitUntil(function($testCase) use ($selector, $selType, &$element) {
                try {
                    if($selType === 'jquery')
                    {
                        $element = $this->byJQ($selector);
                        $boolean = $element->displayed();
                    }
                    elseif($selType === 'xpath')
                    {
                        $element = $testCase->byCssSelector($selector);
                        $boolean = $element->displayed();
	            }
                    else
                    {
                        $element = $this->byXPath($selector);
                        $boolean = $element->displayed();
                    }
                        
                } catch (\Exception $e) {
                    $boolean = false;
                }
                if ($boolean !== true)
                    usleep(500000);
                return $boolean === true ?: null;
            }, $timeout);
            return $element;
        }
        
        /*
         * Does not want working...
         * 
         * public function waitForElement($selector, $timeout = 5000){
            $element = $this->waitUntil(function($testCase) use ($selector) {
                try {
                    $element = $testCase->byCssSelector($selector);
                    if ($element->displayed()) {
                        return $element;
                    }
                } catch (PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {}
            }, $timeout);
            return $element;
        }*/

        public function waitForLocation($url, $timeout = 5000){
            $this->waitUntil(function($testCase) use ($url, $timeout) {
                $bUrl = $testCase->getBrowserUrl() == $url;
                if (!$bUrl)
                    usleep(500000);
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