<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxBaseFormAccount');

/**
 * @see BxBaseFormAccount
 */
class BxTemplFormAccount extends BxBaseFormAccount {
    public function __construct($aInfo, $oTemplate = false) {
        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
