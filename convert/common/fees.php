<?php

trait Fees {
    private $fees_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    public function create_fee($fee) {
        return false;
    }
}
