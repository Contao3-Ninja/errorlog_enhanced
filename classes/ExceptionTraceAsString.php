<?php

/**
 * Contao Open Source CMS,Copyright (c) 2005-2016 Leo Feyer
 *
 * Hook class for enhanced error handler
 *
 * @copyright  Glen Langer 2016 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @license    LGPL
 * @see	       https://github.com/BugBuster1701/errorlog_enhanced
 */



/**
 * Class ExceptionTraceAsString
 *
 * @copyright  Glen Langer 2016 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 */
class ExceptionTraceAsString
{
    public function InitializeSystem()
    {
        include TL_ROOT . '/system/modules/errorlog_enhanced/helper/function_enhanced.php';
        set_error_handler('__error_enhanced');
        set_exception_handler('__exception_enhanced');
    }
}
