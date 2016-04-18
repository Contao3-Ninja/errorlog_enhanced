<?php

/**
 * Contao Open Source CMS,Copyright (c) 2005-2016 Leo Feyer
 *
 * Config for enhanced error handler
 *
 * @copyright  Glen Langer 2016 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @license    LGPL
 * @see	       https://github.com/BugBuster1701/errorlog_enhanced
 */

$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('ExceptionTraceAsString', 'InitializeSystem'); 
