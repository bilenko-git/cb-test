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

        public function waitForElement($selector, $timeout = 5000){
            $_this = $this;
            $this->waitUntil(function() use ($_this, $selector) {
                try {
                    //$boolean = ($_this->byCssSelector($selector) instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
                    $boolean = $_this->execute(array('script' => 'return window.$("'.$selector.'").length>0', 'args' => array()));
                } catch (\Exception $e) {
                    $boolean = false;
                }
                return $boolean === true ?: null;
            }, $timeout);
            return $_this->byCssSelector($selector);
        }

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