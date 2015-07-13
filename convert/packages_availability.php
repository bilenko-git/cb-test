<?php
    namespace MyProject\Tests;
    require_once 'availability_base_test.php';

    class packages_availability extends availability_base_test{
        public function testSteps(){
            $this->loginToSite(function(){
                echo 'Logged in successful';
            }, function(){
                $this->fail('Failed to login to site with such credentials');
            });
        }
    }
?>