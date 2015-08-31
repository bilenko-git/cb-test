<?php
/**
 * User: Alex Manko
 * Date: 31.08.2015
 * Time: 15:56
 */

namespace MyProject\Tests;
require_once 'test_restrict.php';

class login_logout extends test_restrict {
    public function test() {
        $this->url('http://dev.billing.cloudbeds.com/');
    }
}