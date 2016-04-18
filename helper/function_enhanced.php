<?php

/**
 * Contao Open Source CMS,Copyright (c) 2005-2016 Leo Feyer
 * 
 * Functions for enhanced error handler
 *
 * @copyright  Glen Langer 2016 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @license    LGPL
 * @see	       https://github.com/BugBuster1701/errorlog_enhanced
 */

/**
 * Handle errors like PHP does it natively but additionaly log them to the
 * application error log file.
 *
 * @param integer $intType
 * @param string  $strMessage
 * @param string  $strFile
 * @param integer $intLine
 */
function __error_enhanced($intType, $strMessage, $strFile, $intLine)
{
    $arrErrors = array
    (
            E_ERROR             => 'Fatal error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parsing error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Core error',
            E_CORE_WARNING      => 'Core warning',
            E_COMPILE_ERROR     => 'Compile error',
            E_COMPILE_WARNING   => 'Compile warning',
            E_USER_ERROR        => 'Fatal error',
            E_USER_WARNING      => 'Warning',
            E_USER_NOTICE       => 'Notice',
            E_STRICT            => 'Runtime notice',
            E_RECOVERABLE_ERROR => 'Recoverable error',
            E_DEPRECATED        => 'Deprecated notice',
            E_USER_DEPRECATED   => 'Deprecated notice'
    );
    // Only log errors that have been configured to get logged (see #8267)
    if (error_reporting() & $intType)
    {
        $e = new Exception();
        // Log the error
        error_log(sprintf("\nPHP %s: %s in %s on line %s\n%s\n",
                $arrErrors[$intType],
                $strMessage,
                $strFile,
                $intLine,
                getExceptionTraceAsString($e)));
        // Display the error
        if (ini_get('display_errors'))
        {
            $strMessage = sprintf('<strong>%s</strong>: %s in <strong>%s</strong> on line <strong>%s</strong>',
                    $arrErrors[$intType],
                    str_replace(array(TL_ROOT . '/',TL_ROOT . '\\' ), array('',''), $strMessage), //no full path on display
                    str_replace(array(TL_ROOT . '/',TL_ROOT . '\\' ), array('',''), $strFile), // see #4971 + WinFix, / does not always work.
                    $intLine);
            $strMessage .= "\n" . '<pre style="margin:11px 0 0">' . "\n" . str_replace(TL_ROOT . '/', '', getExceptionTraceAsString($e)) . "\n" . '</pre>';
            echo '<br>' . $strMessage;
        }
        // Exit on severe errors
        if (in_array($intType, array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR)))
        {
            show_help_message();
            exit;
        }
    }
}
/**
 * Exception handler
 *
 * Log exceptions in the application log file and print them to the screen
 * if "display_errors" is set. Callback to a custom exception handler defined
 * in the application file "config/error.php".
 *
 * @param Exception|Throwable $e
 */
function __exception_enhanced($e)
{
    // PHP 7 compatibility
    if (!$e instanceof Exception && (!interface_exists('Throwable', false) || !$e instanceof Throwable))
    {
        throw new InvalidArgumentException('Exception or Throwable expected, ' . gettype($e) . ' given');
    }
    error_log(sprintf("PHP Fatal error: Uncaught exception '%s' with message '%s' thrown in %s on line %s\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            getExceptionTraceAsString($e)));
    // Display the exception
    if (ini_get('display_errors'))
    {
        $strMessage = sprintf('<strong>Fatal error</strong>: Uncaught exception <strong>%s</strong> with message
                               <strong>%s</strong> thrown in <strong>%s</strong> on line <strong>%s</strong>',
                get_class($e),
                $e->getMessage(),
                str_replace(array(TL_ROOT . '/',TL_ROOT . '\\' ), array('',''), $e->getFile()), //WinFix, DIRECTORY_SEPARATOR does not always work. 
                $e->getLine());
        $strMessage .= "\n" . '<pre style="margin:11px 0 0">' . "\n" . getExceptionTraceAsString($e) . "\n" . '</pre>';
        echo '<br>' . $strMessage;
    }
    show_help_message();
    exit;
}

/**
 * Original from https://gist.github.com/abtris/1437966
 * Contao 3 Version Glen Langer
 * 
 * @param Exception $exception
 * @return string
 */
function getExceptionTraceAsString($exception)
{
    $rtn = "";
    $count = 0;
    foreach ($exception->getTrace() as $frame)
    {
        $args = ""; if (isset($frame['args']))
        {
            $args = array();
            foreach ($frame['args'] as $arg)
            {
                if (is_string($arg))
                {
                    $args[] = "'" . $arg . "'";
                }
                elseif (is_array($arg))
                {
                    $args[] = "Array";
                }
                elseif (is_null($arg))
                {
                    $args[] = 'NULL';
                }
                elseif (is_bool($arg))
                {
                    $args[] = ($arg) ? "true" : "false";
                }
                elseif (is_object($arg))
                {
                    $args[] = get_class($arg);
                }
                elseif (is_resource($arg))
                {
                    $args[] = get_resource_type($arg);
                }
                else
                {
                    $args[] = $arg;
                }
            } $args = join(", ", $args);
        }
        if (isset($frame['file']))
        {
            //WinFix, DIRECTORY_SEPARATOR does not always work.
            $frame['file'] = str_replace(array(TL_ROOT . '/',TL_ROOT . '\\' ), array('',''), $frame['file']); 
        }
        $args = str_replace(array(TL_ROOT . '/',TL_ROOT . '\\' ), array('',''), $args);
        $rtn .= sprintf( "#%s %s(%s): %s(%s)\n", $count
                                               , isset($frame['file']) ? $frame['file'] : 'unknown file'
                                               , isset($frame['line']) ? $frame['line'] : 'unknown line'
                                               , (isset($frame['class'])) ? $frame['class'].$frame['type'].$frame['function'] : $frame['function']
                                               , $args );
        $count++;
    }
    return $rtn;
}
