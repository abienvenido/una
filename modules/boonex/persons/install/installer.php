<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');

class BxPersonsInstaller extends BxDolStudioInstaller {

    function __construct($aConfig) {
        parent::__construct($aConfig);
    }

    function enable($aParams) {
        $aResult = parent::enable($aParams);

        bx_import('BxDolImageTranscoder');
        $oTranscoder = BxDolImageTranscoder::getObjectInstance('bx_persons_thumb');
        $oTranscoder->registerHandlers();
        $oTranscoder = BxDolImageTranscoder::getObjectInstance('bx_persons_preview');
        $oTranscoder->registerHandlers();

        return $aResult;
    }

    function install($aParams) {

        return parent::install($aParams);
    }

    function uninstall($aParams) {

        return parent::uninstall($aParams);
    }
}

/** @} */ 
