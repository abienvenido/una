<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

class BxDolFile extends BxDol implements iBxDolSingleton
{
    protected $_sPathFrom;
    protected $_sPathTo;
    protected $_bForceOverwrite = false;

    protected function __construct()
    {
        parent::__construct();

        $this->_sPathFrom = BX_DIRECTORY_PATH_ROOT;
        $this->_sPathTo = BX_DIRECTORY_PATH_ROOT;
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolFile();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function copy($sFilePathFrom, $sFilePathTo)
    {
        $sFilePathFrom = $this->_sPathFrom . $sFilePathFrom;
        $sFilePathTo = $this->_sPathTo . $sFilePathTo;
        return $this->_copyFile($sFilePathFrom, $sFilePathTo);
    }

    function delete($sPath)
    {
        $sPath = $this->_sPathTo . $sPath;
        return $this->_deleteDirectory($sPath);
    }

    function getPermissions($sPath)
    {
        $sPath = $this->_sPathTo . $sPath;
        return $this->_getPermissions($sPath);
    }

    function setPermissions($sPath, $sMode)
    {
        $sPath = $this->_sPathTo . $sPath;
        return $this->_setPermissions($sPath, $sMode);
    }

    function setForceOverwrite($b)
    {
        $this->_bForceOverwrite = $b;
    }

    function getForceOverwrite()
    {
        return $this->_bForceOverwrite;
    }

    protected function _copyFile($sFilePathFrom, $sFilePathTo)
    {
        if(!$this->getForceOverwrite() && file_exists($sFilePathTo))
            return true;

        if(substr($sFilePathFrom, -1) == '*')
            $sFilePathFrom = substr($sFilePathFrom, 0, -1);

        //--- Copy file to file/folder
        if(is_file($sFilePathFrom)) {
            if($this->_isFile($sFilePathTo)) {
                $aFileParts = $this->_parseFile($sFilePathTo);
                if(isset($aFileParts[0]))
                    @mkdir($aFileParts[0]);

                return @copy($sFilePathFrom, $sFilePathTo);
            }
            else {
                @mkdir($sFilePathTo);

                $aFileParts = $this->_parseFile($sFilePathFrom);
				return @copy($sFilePathFrom, $this->_validatePath($sFilePathTo) . $aFileParts[1]);
            }
        }

        //--- Copy directory to directory
        if(is_dir($sFilePathFrom) && $this->_isDirectory($sFilePathTo)) {
            @mkdir($sFilePathTo);

            $bResult = true;
            $aInnerFiles = $this->_readDirectory($sFilePathFrom);
            foreach($aInnerFiles as $sFile)
                $bResult = $bResult && $this->_copyFile($this->_validatePath($sFilePathFrom) . $sFile, $this->_validatePath($sFilePathTo) . $sFile);

			return $bResult;
        }

        return false;
    }

    protected function _readDirectory($sFilePath)
    {
        if(!is_dir($sFilePath) || !($rSource = opendir($sFilePath)))
            return false;

        $aResult = array();
        while(($sFile = readdir($rSource)) !== false) {
            if($sFile == '.' || $sFile =='..')
                continue;
            $aResult[] = $sFile;
        }
        closedir($rSource);

        return $aResult;
    }

    protected function _deleteDirectory($sPath)
    {
        if(!file_exists($sPath))
            return true;

        if($this->_isDirectory($sPath)) {
        	$sPath = $this->_validatePath($sPath);

            $aFiles = $this->_readDirectory($sPath);
            if(is_array($aFiles) && !empty($aFiles))
                foreach($aFiles as $sFile)
                    $this->_deleteDirectory($sPath . $sFile);

            if(!@rmdir($sPath))
                return false;
        } else if(!@unlink($sPath))
            return false;

        return true;
    }

    protected function _validatePath($sPath)
    {
        return $sPath . ($sPath && !$this->_isEndWithSlash($sPath) && $this->_isDirectory($sPath) ? '/' : '');
    }

    protected function _parseFile($sFilePath)
    {
        return array(dirname($sFilePath), basename($sFilePath));
    }

    protected function _isFile($sFilePath)
    {
    	if($this->_isEndWithSlash($sFilePath))
    		return false;

    	$aInfo = pathinfo($sFilePath);
    	if(!isset($aInfo['extension']))
    		return false;

        return true;
    }

    protected function _isDirectory($sFilePath)
    {
        return !$this->_isFile($sFilePath);
    }

    protected function _isEndWithSlash($sFilePath)
    {
    	return in_array(substr($sFilePath, -1), array('/', '\\'));
    }

    protected function _getPermissions($sPath)
    {
        clearstatcache();

        $hPerms = @fileperms($sPath);
        if($hPerms == false)
            return false;

        return substr(decoct($hPerms), -3);
    }

    protected function _setPermissions($sPath, $sMode)
    {
        $aConvert = array('writable' => 0666, 'executable' => 0777);

        return chmod($sPath, $aConvert[$sMode]);
    }
}

/** @} */
