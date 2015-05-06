<?
    trait Waiters{
        public function waitForElement($selector, $timeout = 5000, $assert = false, $message = ''){
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