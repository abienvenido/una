<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolModuleQuery');
bx_import('BxTemplStudioPage');
bx_import('BxDolStudioLanguagesQuery');

define('BX_DOL_STUDIO_LANG_DEFAULT', 'en');
define('BX_DOL_STUDIO_LANG_TYPE_DEFAULT', 'general');

class BxDolStudioLanguage extends BxTemplStudioPage {
	protected $oDb;
	protected $sLanguage;
	protected $aLanguage;

    function BxDolStudioLanguage($sLanguage, $sPage) {
        parent::BxTemplStudioPage($sLanguage);

        $this->oDb = new BxDolStudioLanguagesQuery();

        $this->sLanguage = BX_DOL_STUDIO_LANG_DEFAULT;
        if(is_string($sLanguage) && !empty($sLanguage))
            $this->sLanguage = $sLanguage;

        $this->sPage = BX_DOL_STUDIO_LANG_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;

        //--- Check actions ---//
        if(($sAction = bx_get('lang_action')) !== false) {
	        $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_pgt_err_cannot_process_action'));
	        switch($sAction) {
	            case 'activate':
	                $sValue = bx_process_input(bx_get('lang_value'));
	                if(empty($sValue))
	                    break;

	                $aResult = $this->activate($sValue);
	                break;
	        }

	        $oJson = new Services_JSON();		        
            echo $oJson->encode($aResult);
            exit;
        }

        $this->aLanguage = BxDolModuleQuery::getInstance()->getModuleByName($this->sLanguage);
        if(empty($this->aLanguage) || !is_array($this->aLanguage))
            BxDolStudioTemplate::getInstance()->displayPageNotFound();

        $this->aPage['header'] = $this->aLanguage['title'];
        $this->aPage['caption'] = $this->aLanguage['title'];

        $this->addAction(array(
            'type' => 'switcher',
        	'name' => 'activate',
        	'caption' => '_adm_txt_pca_active',
            'checked' => (int)$this->aLanguage['enabled'] == 1,
            'onchange' => "javascript:" . $this->getPageJsObject() . ".activate('" . $this->sLanguage . "', this)"
        ), false);
    }

    function activate($sLanguage) {
        $aLanguage = BxDolModuleQuery::getInstance()->getModuleByName($sLanguage);
        if(empty($aLanguage) || !is_array($aLanguage))
            return array('code' => 1, 'message' => _t('_adm_err_operation_failed'));

        $aLanguages = array();
        $iLanguages = $this->oDb->getLanguagesBy(array('type' => 'active'), $aLanguages);
        if($iLanguages == 1 && $aLanguages[0]['name'] == $sLanguage)
            return array('code' => 1, 'message' => _t('_adm_pgt_err_last_active'));

        $sLanguageDefault = $this->oDb->getParam('lang_default');
        if($aLanguage['uri'] == $sLanguageDefault)
            return array('code' => 2, 'message' => _t('_adm_pgt_err_deactivate_default'));

        bx_import('BxDolStudioInstallerUtils');
        $oInstallerUtils = BxDolStudioInstallerUtils::getInstance();

        $aResult = (int)$aLanguage['enabled'] == 0 ? $oInstallerUtils->perform($aLanguage['path'], 'enable') : $oInstallerUtils->perform($aLanguage['path'], 'disable');
        if($aResult['code'] != 0)
            return $aResult;

        bx_import('BxDolStudioTemplate');
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aResult = array('code' => 0, 'message' => _t('_adm_scs_operation_done'));
        if((int)$aLanguage['enabled'] == 0) {
            $aResult['content'] = $oTemplate->parseHtmlByName('page_content.html', array(
                'page_menu_code' => $this->getPageMenu(),
                'page_main_code' => $this->getPageCode()
            ));
        }
        else
            $aResult['content'] = "";

        return $aResult;
    }
}
/** @} */