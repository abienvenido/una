<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxBaseVotingView');

/**
 * @see BxDolVoting
 */
class BxTemplVotingView extends BxBaseVotingView
{
    function BxTemplVotingView( $sSystem, $iId, $iInit = 1 )
    {
        BxBaseVotingView::BxBaseVotingView( $sSystem, $iId, $iInit );
    }
}

