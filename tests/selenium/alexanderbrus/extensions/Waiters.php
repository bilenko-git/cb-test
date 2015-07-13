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
        public function waitForElement($selector, $timeout = 5000, $selType='jQ'){
            $this->waitUntil(function($testCase) use ($selector, $selType) {
                try {
                    if($selType === 'jQ')
                    {
                        $boolean = $testCase->execute(array('script' => 'return window.$("'.$selector.'").length>0', 'args' => array()));
                    }
                    elseif($selType === 'css')
                    {
                        $element = $testCase->byCssSelector($selector);
                        $boolean = $element->displayed();
                    }
                        
                } catch (\Exception $e) {
                    $boolean = false;
                }
                return $boolean === true ?: null;
            }, $timeout);
            return $this->byCssSelector($selector);
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
            $this->waitUntil(function($testCase) use ($url, $timeout) {return $testCase->getBrowserUrl() == $url;}, $timeout);
        }

        public function waitUntilVisible($element, $timeout = 1000){
            $this->waitUntil(function() use($element){
                return $element->displayed();
            }, $timeout);
        }
    }
?>