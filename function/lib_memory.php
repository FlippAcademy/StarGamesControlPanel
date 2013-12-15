<?php
/* phpMyAdmin Functions */

/**
 * calls $function vor every element in $array recursively
 *
 * @param   array   $array      array to walk
 * @param   string  $function   function to call for every array element
 */
function CP_arrayWalkRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            CP_arrayWalkRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }

        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}

function CP_getenv($var_name) {
    if (isset($_SERVER[$var_name])) {
        return $_SERVER[$var_name];
    } elseif (isset($_ENV[$var_name])) {
        return $_ENV[$var_name];
    } elseif (getenv($var_name)) {
        return getenv($var_name);
    } elseif (function_exists('apache_getenv')
     && apache_getenv($var_name, true)) {
        return apache_getenv($var_name, true);
    }

    return '';
}

    /**
     * @static
     */
    function isHttps()
    {
        $is_https = false;

        $url = array();

        // At first we try to parse REQUEST_URI, it might contain full URL,
        if (CP_getenv('REQUEST_URI')) {
            $url = @parse_url(CP_getenv('REQUEST_URI')); // produces E_WARNING if it cannot get parsed, e.g. '/foobar:/'
            if($url === false) {
                $url = array();
            }
        }

        // If we don't have scheme, we didn't have full URL so we need to
        // dig deeper
        if (empty($url['scheme'])) {
            // Scheme
            if (CP_getenv('HTTP_SCHEME')) {
                $url['scheme'] = CP_getenv('HTTP_SCHEME');
            } else {
                $url['scheme'] =
                    CP_getenv('HTTPS') && strtolower(CP_getenv('HTTPS')) != 'off'
                        ? 'https'
                        : 'http';
            }
        }

        if (isset($url['scheme'])
          && $url['scheme'] == 'https') {
            $is_https = true;
        } else {
            $is_https = false;
        }

        return $is_https;
    }

    function getCookiePath()
    {
        static $cookie_path = null;

        if (null !== $cookie_path) {
            return $cookie_path;
        }

        $url = '';

        if (CP_getenv('REQUEST_URI')) {
            $url = CP_getenv('REQUEST_URI');
        }

        // If we don't have path
        if (empty($url)) {
            if (CP_getenv('PATH_INFO')) {
                $url = CP_getenv('PATH_INFO');
            } elseif (CP_getenv('PHP_SELF')) {
                // PHP_SELF in CGI often points to cgi executable, so use it
                // as last choice
                $url = CP_getenv('PHP_SELF');
            } elseif (CP_getenv('SCRIPT_NAME')) {
                $url = CP_getenv('PHP_SELF');
            }
        }

        $parsed_url = @parse_url($_SERVER['REQUEST_URI']); // produces E_WARNING if it cannot get parsed, e.g. '/foobar:/'
        if ($parsed_url === false) {
            $parsed_url = array('path' => $url);
        }

        $cookie_path   = substr($parsed_url['path'], 0, strrpos($parsed_url['path'], '/'))  . '/';

        return $cookie_path;
    }

/**
 * removes cookie
 *
 * @uses    isHttps()
 * @uses    getCookiePath()
 * @uses    setcookie()
 * @uses    time()
 * @param   string  $cookie     name of cookie to remove
 * @return  boolean result of setcookie()
 */
function CP_removeCookie($cookie)
{
    return setcookie($cookie, '', time() - 3600,
        getCookiePath(), '', isHttps());
}

/**
 * sets cookie if value is different from current cokkie value,
 * or removes if value is equal to default
 *
 * @uses    isHttps()
 * @uses    getCookiePath()
 * @uses    $_COOKIE
 * @uses    CP_removeCookie()
 * @uses    setcookie()
 * @uses    time()
 * @param   string  $cookie     name of cookie to remove
 * @param   mixed   $value      new cookie value
 * @param   string  $default    default value
 * @param   int     $validity   validity of cookie in seconds (default is one month)
 * @param   bool    $httponlt   whether cookie is only for HTTP (and not for scripts)
 * @return  boolean result of setcookie()
 */
function CP_setCookie($cookie, $value, $default = null, $validity = null, $httponly = true)
{
    if ($validity == null) {
        $validity = 2592000;
    }
    if (strlen($value) && null !== $default && $value === $default
     && isset($_COOKIE[$cookie])) {
        // remove cookie, default value is used
        return CP_removeCookie($cookie);
    }

    if (! strlen($value) && isset($_COOKIE[$cookie])) {
        // remove cookie, value is empty
        return CP_removeCookie($cookie);
    }

    if (! isset($_COOKIE[$cookie]) || $_COOKIE[$cookie] !== $value) {
        // set cookie with new value
        /* Calculate cookie validity */
        if ($validity == 0) {
            $v = 0;
        } else {
            $v = time() + $validity;
        }
        /* Use native support for httponly cookies if available */
        if (version_compare(PHP_VERSION, '5.2.0', 'ge')) {
            return setcookie($cookie, $value, $v,
                getCookiePath(), '', isHttps(), $httponly);
        } else {
            return setcookie($cookie, $value, $v,
                getCookiePath() . ($httponly ? '; HttpOnly' : ''), '', isHttps());
        }
    }

    // cookie has already $value as value
    return true;
}

?>