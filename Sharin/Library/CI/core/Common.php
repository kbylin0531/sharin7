<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    CodeIgniter
 * @author    EllisLab Dev Team
 * @copyright    Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright    Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license    http://opensource.org/licenses/MIT	MIT License
 * @link    https://codeigniter.com
 * @since    Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');
use Sharin\Library\CI;
/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package        CodeIgniter
 * @subpackage    CodeIgniter
 * @category    Common Functions
 * @author        EllisLab Dev Team
 * @link        https://codeigniter.com/user_guide/
 */

// ------------------------------------------------------------------------

/**
 * Determines if the current version of PHP is equal to or greater than the supplied value
 *
 * @param    string
 * @return    bool    TRUE if the current version is $version or higher
 */
function is_php($version) {
    static $_is_php;
    $version = (string)$version;

    if (!isset($_is_php[$version])) {
        $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
    }

    return $_is_php[$version];
}

/**
 * Tests for file writability
 *
 * is_writable() returns TRUE on Windows servers when you really can't write to
 * the file, based on the read-only attribute. is_writable() is also unreliable
 * on Unix servers if safe_mode is on.
 *
 * @link    https://bugs.php.net/bug.php?id=54709
 * @param    string
 * @return    bool
 */
function is_really_writable($file)
{
    // If we're on a Unix server with safe_mode off we call is_writable
    if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') OR !ini_get('safe_mode'))) {
        return is_writable($file);
    }

    /* For Windows servers and safe_mode "on" installations we'll actually
     * write a file then read it. Bah...
     */
    if (is_dir($file)) {
        $file = rtrim($file, '/') . '/' . md5(mt_rand());
        if (($fp = @fopen($file, 'ab')) === FALSE) {
            return FALSE;
        }

        fclose($fp);
        @chmod($file, 0777);
        @unlink($file);
        return TRUE;
    } elseif (!is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
        return FALSE;
    }

    fclose($fp);
    return TRUE;
}

// ------------------------------------------------------------------------

function &load_class($class, $directory = 'libraries', $param = NULL) {
    return CI::loadClass($class,$directory,$param);
}

/**
 * Keeps track of which libraries have been loaded. This function is
 * called by the load_class() function above
 *
 * @param    string
 * @return    array
 */
function &is_loaded($class = '')
{
    return CI::isLoaded($class);
}

/**
 * Reference to the CI_Controller method.
 *
 * Returns current CI instance object
 *
 * @return CI_Controller
 */
function &get_instance()
{
    return CI_Controller::get_instance();
}
/**
 * Loads the main config.php file
 *
 * This function lets us grab the config file even if the Config class
 * hasn't been instantiated yet
 *
 * @param    array
 * @return    array
 */
function &get_config(Array $replace = array())
{
    return CI::getConfig($replace);
}

/**
 * Returns the specified config item
 *
 * @param    string
 * @return    mixed
 */
function config_item($item)
{
    return CI::configItem($item);
}

/**
 * Returns the MIME types array from config/mimes.php
 *
 * @return    array
 */
function &get_mimes()
{
    return CI::getMimes();
}

/**
 * Is HTTPS?
 *
 * Determines if the application is accessed via an encrypted
 * (HTTPS) connection.
 *
 * @return    bool
 */
function is_https()
{
    return CI::isHttps();
}

/**
 * Is CLI?
 *
 * Test to see if a request was made from the command line.
 *
 * @return    bool
 */
function is_cli()
{
    return CI::isCli();
}

// ------------------------------------------------------------------------

if (!function_exists('show_error')) {
    /**
     * Error Handler
     *
     * This function lets us invoke the exception class and
     * display errors using the standard error template located
     * in application/views/errors/error_general.php
     * This function will send the error page directly to the
     * browser and exit.
     *
     * @param    string
     * @param    int
     * @param    string
     * @return    void
     */
    function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
    {
        $status_code = abs($status_code);
        if ($status_code < 100) {
            $exit_status = $status_code + 9; // 9 is EXIT__AUTO_MIN
            if ($exit_status > 125) // 125 is EXIT__AUTO_MAX
            {
                $exit_status = 1; // EXIT_ERROR
            }

            $status_code = 500;
        } else {
            $exit_status = 1; // EXIT_ERROR
        }

        $_error =& load_class('Exceptions', 'core');
        echo $_error->show_error($heading, $message, 'error_general', $status_code);
        exit($exit_status);
    }
}

// ------------------------------------------------------------------------

if (!function_exists('show_404')) {
    /**
     * 404 Page Handler
     *
     * This function is similar to the show_error() function above
     * However, instead of the standard error template it displays
     * 404 errors.
     *
     * @param    string
     * @param    bool
     * @return    void
     */
    function show_404($page = '', $log_error = TRUE)
    {
        $_error =& load_class('Exceptions', 'core');
        $_error->show_404($page, $log_error);
        exit(4); // EXIT_UNKNOWN_FILE
    }
}

// ------------------------------------------------------------------------

if (!function_exists('log_message')) {
    /**
     * Error Logging Interface
     *
     * We use this as a simple mechanism to access the logging
     * class and send messages to be logged.
     *
     * @param    string    the error level: 'error', 'debug' or 'info'
     * @param    string    the error message
     * @return    void
     */
    function log_message($level, $message)
    {
        static $_log;

        if ($_log === NULL) {
            // references cannot be directly assigned to static variables, so we use an array
            $_log[0] =& load_class('Log', 'core');
        }

        $_log[0]->write_log($level, $message);
    }
}

/**
 * Set HTTP Status Header
 *
 * @param    int|string $code the status code
 * @param    string
 * @return    void
 */
function set_status_header($code = 200, $text = '')
{
    CI::setStatusHeader($code,$text);
}



function remove_invisible_characters($str, $url_encoded = TRUE)
{
    return CI::removeInvisibleCharacters($str,$url_encoded);
}
function html_escape($var, $double_encode = TRUE)
{
    return CI::htmlEscape($var,$double_encode);
}
function _stringify_attributes($attributes, $js = FALSE)
{
    return CI::stringifyAttributes($attributes,$js);
}
function function_usable($function_name)
{
    CI::functionUsable($function_name);
}
