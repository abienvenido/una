<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * General class for module menu.
 */
class BxGroupsMenu extends BxBaseModGeneralMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_groups';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
