<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioModules');
bx_import('BxTemplStudioFunctions');
bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioDesignsQuery');

class BxDolStudioDesigns extends BxTemplStudioModules {
    function BxDolStudioDesigns() {
        parent::BxTemplStudioModules();

        $this->oDb = new BxDolStudioDesignsQuery();

        $this->sJsObject = 'oBxDolStudioDesigns';
        $this->sLangPrefix = 'dsn';
        $this->sTemplPrefix = 'dsn';
        $this->sParamPrefix = 'dsn';
    }
}
/** @} */