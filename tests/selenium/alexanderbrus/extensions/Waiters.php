<?
    trait Waiters{
        public function waitForElement($selector, $timeout = 5000){
            $_this = $this;
            $this->waitUntil(function() use ($_this, $selector) {
                try {
                    $boolean = ($_this->byCssSelector($selector) instanceof \PHPUnit_Extensions_Selenium2TestCase_Element);
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