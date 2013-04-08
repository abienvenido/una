<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioLanguage');

class BxBaseStudioLanguage extends BxDolStudioLanguage {
    protected $aMenuItems = array(
    	'general' => '_adm_lmi_cpt_general'
    );

    function BxBaseStudioLanguage($sLanguage = "", $sPage = "") {
        parent::BxDolStudioLanguage($sLanguage, $sPage);
    }
    function getPageCss() {
        return array_merge(parent::getPageCss(), array());
    }
    function getPageJs() {
        return array_merge(parent::getPageJs(), array('settings.js', 'language.js'));
    }
    function getPageJsObject() {
        return 'oBxDolStudioLanguage';
    }
    function getPageCaption() {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
        	'content' => parent::getPageCaption(),
        );
        return $oTemplate->parseHtmlByName('lang_page_caption.html', $aTmplVars);
    }
    function getPageAttributes() {
        if((int)$this->aLanguage['enabled'] == 0)
        	return 'style="display:none"';

        return parent::getPageAttributes();
    }

    function getPageMenu() {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        foreach($this->aMenuItems as $sName => $sCaption)
            $aMenu[] = array(
                'name' => $sName,
                'icon' => 'mi-lang-' . $sName . '.png',
            	'link' => BX_DOL_URL_STUDIO . 'languages.php?name=' . $this->sLanguage . '&page=' . $sName,
            	'title' => _t($sCaption),
            	'selected' => $sName == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }

    function getPageCode() {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        if((int)$this->aLanguage['enabled'] != 1)
            BxDolStudioTemplate::getInstance()->addInjection('injection_bg_style', 'text', ' bx-std-page-bg-empty');

        return $this->$sMethod();
    }

    protected function getGeneral() {
        $oTemplate = BxDolStudioTemplate::getInstance();

        bx_import('BxTemplStudioSettings');
        $oPage = new BxTemplStudioSettings($this->sLanguage);

        $aTmplVars = array(
        	'bx_repeat:blocks' => $oPage->getPageCode(),
        );
        return $oTemplate->parseHtmlByName('language.html', $aTmplVars);
    }
}
/** @} */