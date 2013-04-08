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

bx_import('BxDolStudioNavigation');

class BxBaseStudioNavigation extends BxDolStudioNavigation {
    protected $sSubpageUrl;
    protected $aGridObjects = array(
        'menus' => 'sys_studio_nav_menus',
        'sets' => 'sys_studio_nav_sets',
        'items' => 'sys_studio_nav_items'
    );

    function BxBaseStudioNavigation($sPage = '') {
        parent::BxDolStudioNavigation($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'builder_menu.php?page=';
    }
    function getPageCss() {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css', 'navigation.css'));
    }
    function getPageJs() {
        return array_merge(parent::getPageJs(), array());
    }
    function getPageJsObject() {
        return '';
    }
    function getPageMenu() {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array(
            BX_DOL_STUDIO_NAV_TYPE_MENUS, 
            BX_DOL_STUDIO_NAV_TYPE_SETS, 
            BX_DOL_STUDIO_NAV_TYPE_ITEMS
        );
        foreach($aMenuItems as $sMenuItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => 'mi-nav-' . $sMenuItem . '.png',
            	'link' => $this->sSubpageUrl . $sMenuItem,
            	'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
            	'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }
    function getPageCode() {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }

    function actionGetSets() {
        if(($sModule = bx_get('nav_module')) === false)
            return array('code' => 2, 'message' => _t('_adm_nav_err_missing_params'));

        $sModule = bx_process_input($sModule);
        return array('code' => 0, 'message' => '', 'content' => $this->getItemsObject()->getSetsSelector($sModule));
    }

    protected function getMenus() {
        return $this->getGrid($this->aGridObjects['menus']);
    }

    protected function getSets() {
        return $this->getGrid($this->aGridObjects['sets']);
    }

    protected function getItems() {
        return $this->getGrid($this->aGridObjects['items']);
    }

    protected function getItemsObject() {
        return $this->getGridObject($this->aGridObjects['items']);
    }

    protected function getGridObject($sObjectName) {
        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return $oGrid;
    }

    protected function getGrid($sObjectName) {
        $oTemplate = BxDolStudioTemplate::getInstance();

        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
        	'bx_repeat:blocks' => array(
                array(
                	'caption' => '',
                    'panel_top' => '',
                    'items' => $oGrid->getCode(),
                    'panel_bottom' => ''
                )
            )
        );

        return $oTemplate->parseHtmlByName('navigation.html', $aTmplVars);
    }
}
/** @} */