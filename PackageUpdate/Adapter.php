<?php
/**
 * Base class for PEAR_PackageUpdate adapters
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * CREDITS: To Alexey Borzov <avb@php.net>
 *          for his idea on adapters of HTTP_Request2
 *
 * @category PEAR
 * @package  PEAR_PackageUpdate
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    File available since Release 1.1.0a1
 */


/**
 * Base class for PEAR_PackageUpdate adapters
 *
 * PEAR_PackageUpdate class itself only defines methods for aggregating the request
 * data, all actual work of sending the request to the remote server and
 * receiving its response is performed by adapters.
 *
 * @category PEAR
 * @package  PEAR_PackageUpdate
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    Class available since Release 1.1.0a1
 * @abstract
 */

class PEAR_PackageUpdate_Adapter
{
    /**
     * Check if the protocol asked is supported
     *
     * Check if the protocol asked is supported by default or package channel
     *
     * @abstract
     * @access public
     * @return bool
     * @since  version 1.1.0a1 (2009-02-28)
     */
    function supports()
    {
    }

    /**
     * Sends request to the remote server
     *
     * Sends request to the remote server and returns its response
     *
     * @param string $request Remote request id to proceed
     *
     * @abstract
     * @access public
     * @return mixed
     * @since  version 1.1.0a1 (2009-02-28)
     */
    function sendRequest($request)
    {
    }
}
?>