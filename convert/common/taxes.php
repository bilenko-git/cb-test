<?php

trait Taxes {
    private $taxes_url = 'http://{server}/connect/{property_id}#/fees_and_taxes';

    public function create_tax($tax) {
        return false;
    }
}
