<?php
    trait Manipulations{
        protected $_map= array(
            'dashboard' => array('action' => 'click', 'css selector' => '#main_menu [name="adashboard"]'),
            'calendar' => array('action' => 'click', 'css selector' => '#main_menu [name="acalendar"]'),
            'newreservations' => array('action' => 'click', 'css selector' => '#main_menu [name="anewreservations"]'),
            'house_accounts' => array('action' => 'click', 'css selector' => '#main_menu [name="ahouse_accounts"]'),

            'rates' => '#main_menu [name="arates"]',
            'roomrates' => array('action' => 'click', 'css selector' => '#main_menu #sroomRates > a', 'parent' => 'rates'),
            'packages' => array('action' => 'click', 'css selector' => '#main_menu #spackages > a', 'parent' => 'rates'),
            'roomblocks' => array('action' => 'click', 'css selector' => '#main_menu #sroomblocks > a', 'parent' => 'rates'),

            'customers' => array('action' => 'click', 'css selector' => '#main_menu [name="acustomers"]'),

            'reports' => array('action' => 'click', 'css selector' => '#main_menu [name="areports"]'),

            'production_reports_group' => array('action' => 'click', '#main_menu #sproduction_reports_group > a', 'parent' => 'reports'),
            'revpar_report' => array('action' => 'click', 'css selector' => '#main_menu #sRevPar_report > a', 'parent' => 'production_reports_group'),
            'occupancy_report' => array('action' => 'click', 'css selector' => '#main_menu #soccupancy_report > a', 'parent' => 'production_reports_group'),
            'adr_report' => array('action' => 'click', 'css selector' => '#main_menu #sADR_report', 'parent' => 'production_reports_group'),
            'channel_production' => array('action' => 'click', 'css selector' => '#main_menu #schannel_production > a', 'parent' => 'production_reports_group'),

            'financial_reports_group' => array('action' => 'click', 'css selector' => '#main_menu #sfinancial_reports_group > a', 'parent' => 'reports'),//not a link
            'commission_report' => array('action' => 'click', 'css selector' => '#main_menu #scommission_report > a', 'parent' => 'financial_reports_group'),
            'report_transactions' => array('action' => 'click', 'css selector' => '#main_menu #sreport_transactions > a', 'parent' => 'financial_reports_group'),
            'daily_report' => array('action' => 'click', 'css selector' => '#main_menu #sdaily_report > a', 'parent' => 'financial_reports_group'),

            'daily_activity_reports_group' => array('action' => 'click', 'css selector' => '#main_menu #sdaily_activity_reports_group > a', 'parent' => 'reports'),
            'account_balances_report' => array('action' => 'click', 'css selector' => '#main_menu #saccount_balances_report > a', 'parent' => 'daily_activity_reports_group'),
            'cashier_report' => array('action' => 'click', 'css selector' => '#main_menu #scashier_report > a', 'parent' => 'daily_activity_reports_group'),

            'settings' => array('action' => 'click', 'css selector' => '#ssettings > a'),
            'profile' => array('action' => 'click', 'css selector' => '#sprofile1 > a', 'parent' => 'settings'),
            'profileuser' => array('action' => 'click', 'css selector' => '#sprofileUser > a', 'parent' => 'profile'),
            'profilehotel' => array('action' => 'click', 'css selector' => '#sprofileHotel > a', 'parent' => 'profile'),
            'users' => array('action' => 'click', 'css selector' => '#susers > a', 'parent' => 'profile'),
            'registrationcards' => array('action' => 'click', 'css selector' => '#sregistrationCards > a', 'parent' => 'profile'),

            'support' => array('action' => 'click', 'css selector' => '#ssupport > a'),

            'scashier_system' => array('action' => 'click', 'css selector' => '#scashier_system > a')
        );

        /**@element is object of PHPUnit_.._Element or selector (use @selector_type to use xpath or other type of selectors)*/
        public function hover($element, $selector_type = 'css selector'){
            if(is_string($element) && !empty($selector_type)){
                $element = $this->element($this->using($selector_type)->value($element));
            }

            if($element->displayed()){
                $size = $element->size();
                $offsetX = intval($size['width'] / 2);
                $offsetY = intval($size['height'] / 2);
                $this->moveto(array(
                    'element' => $element,
                    'xoffset' => $offsetX,
                    'yoffset' => $offsetY
                ));
            }
        }

        public function setAttribute($element, $name, $value){
            $this->execute(array(
                'script' => 'arguments[0].setAttribute("'.$name.'", "'.$value.'")',
                'args' => array($element->toWebDriverObject())
            ));
        }

        public function getAttribute($element, $name){
            return $this->execute(array(
                'script' => 'return arguments[0].getAttribute("'.$name.'")',
                'args' => array($element->toWebDriverObject())
            ));
        }

        public function isChecked($element){
            return $this->execute(array(
                'script' => 'return arguments[0].checked',
                'args' => array($element->toWebDriverObject())
            ));
        }

        public function goToLocation($location){
            $path = $this->getLocationPath($location);

            if(!empty($path)){
                $path_length = count($path);
                foreach($path as $index => $p){
                    $element = $this->element($this->using('css selector')->value($p['css selector']));
                    if($element->displayed()) {
                        if ($p['action'] == 'click' && ($index != 1 && $path_length > 2)) {
                            $element->click();
                        } else {
                            $this->hover($element);
                        }
                    }else{
                        $this->waitToVisible($element, 500);
                        if(!$element->displayed()) return false;
                    }
                }

                return true;
            }

            return false;
        }
        public function getLocationPath($location){
            $path = array();
            if(isset($this->_map[$location])){
                $currentLocation = $this->_map[$location];
                $path[] = $currentLocation;

                if(isset($currentLocation['parent'])) {
                    $path = array_merge($this->getLocationPath($currentLocation['parent']), $path);
                }

                return $path;
            }

            return array();
        }

        public function uploadFileToElement($css_selector, $files){
            $files = (array) $files;
            foreach($files as $file){
                echo 'Wait for file input';
                $tmp1 = $this->elements( $this->using('css selector')->value($css_selector));
                $input_file = end($tmp1);
                echo 'Wait complete;';
                if($input_file instanceof PHPUnit_Extensions_Selenium2TestCase_Element) {
                    echo 'File input set to visible';
                    $this->setAttribute($input_file, 'style', 'display: block!important;');
                    echo 'File input set value';
                    //$input_file->value($file);
                    $this->sendKeys($input_file, $file);
                }
            }
        }
    }
?>