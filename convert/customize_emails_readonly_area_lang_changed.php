<?php
//HOTELS-9833
namespace MyProject\Tests;
require_once 'test_restrict.php';

class customize_emails_readonly_area_lang_changed extends test_restrict
{
    private $customize_email_readonly_section_translation = 'http://{server}/connect/{property_id}#/emails';

    public function test_readonly_area_translation(){
        $this->go_to_customize_email_page();

        $this->waitForElement('.emails_title', 15000, 'jQ');

        $this->change_tab('[href=\'#not_confirmed\']');
        $this->checkAllLangs();

        $this->change_tab('[href=\'#confirmed\']');
        $this->checkAllLangs();

        $this->change_tab('[href=\'#canceled\']');
        $this->checkAllLangs();
    }

    public function checkAllLangs(){
        $all_langs = $this->get_langs();
        foreach($all_langs as $lang){
            $this->change_language($lang);
            $this->checkReadOnlySectionLang($lang);
        }
    }

    public function go_to_customize_email_page(){
        //$this->setupInfo('', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);//for 31 hotel
        //$this->setupInfo('', 'aleksandr.brus+20150715@cloudbeds.com', 'KNS16111988', 412);//for 412 - my demo hotel on dev3


       // $this->setupInfo('wwwdev3.ondeficar.com', 'engineering@cloudbeds.com', 'cl0udb3ds', 31);//for 31 hotel
        $this->setupInfo1('PMS_dev3','PMS');

        $this->loginToSite();
        $customize_email_page = $this->_prepareUrl($this->customize_email_readonly_section_translation);
        $this->url($customize_email_page);
        $this->waitForLocation($customize_email_page);
    }

    public function change_tab($selector){
        $tab = $this->waitForElement($selector, 5000, 'jQ', true);
        $tab->click();
    }

    public function get_langs(){
        return $this->execJS('return $.map($(\'[name="language"] option\', \'#layout\'), function(option){return {"id": option.id, "value": option.value};});');
    }

    public function get_random_lang(){
        $langs = $this->get_langs();
        $rand_key = array_rand($langs);
        return $langs[$rand_key];
    }

    public function change_language($lang){
        $this->waitForElement('.dd.ddcommon', 5000, 'jQ', true)->click();
        $this->waitForElement('li[title="'.$lang['id'].'"]')->click();
    }

    public function checkReadOnlySectionLang($lang){
        try {
            $is_text = $this->waitForElement('.not_editable_lang[data-language=\'' . $lang['value'] . '\']', 5000, 'jQ', false) !== false;
            $this->assertEquals(true, $is_text, 'Language is: ' . $lang['value'] . ' but text was not changed');
        }catch(\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e){
            $this->fail('Translation for '.$lang['value'].' was not found');
        }
    }
}
?>