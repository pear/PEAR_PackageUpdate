<?php
/**
 * XMLRPC-based adapter for PEAR_PackageUpdate
 *
 * PHP versions 4 and 5
 *
 * @category PEAR
 * @package  PEAR_PackageUpdate
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    File available since Release 1.1.0a1
 */

require_once 'PEAR/PackageUpdate/Adapter.php';

/**
 * XMLRPC-based adapter for PEAR_PackageUpdate
 *
 * @category PEAR
 * @package  PEAR_PackageUpdate
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    Class available since Release 1.1.0a1
 */

class PEAR_PackageUpdate_Adapter_XmlRPC extends PEAR_PackageUpdate_Adapter
{
    var $channel;
    var $config;
    var $ppu;

    function PEAR_PackageUpdate_Adapter_XmlRPC(&$config, &$ppu)
    {
        $this->config  =&$config;
        $this->ppu     =&$ppu;
        $this->channel = null;
    }

    /**
     * Check if the protocol asked is supported
     *
     * Check if the protocol asked is supported by default or package channel
     *
     * @access public
     * @return bool
     * @since  version 1.1.0a1 (2009-02-28)
     */
    function supports()
    {
        // Get the config's registry object
        $reg  = &$this->config->getRegistry();
        if ($reg === false) {
            return false;
        }

        // Get the registry's channel object
        $chan = &$reg->getChannel($this->ppu->channel);
        if (PEAR::isError($chan)) {
            return false;
        }
        $this->channel = $chan;

        $mirror = $this->config->get('preferred_mirror');
        return $chan->supports('xmlrpc', 'package.info', $mirror);
    }

    /**
     * Sends request to the remote server
     *
     * Sends request to the remote server and returns its response
     *
     * @param string $request Remote request id to proceed
     *
     * @access public
     * @return mixed
     * @since  version 1.1.0a1 (2009-02-28)
     */
    function sendRequest($request)
    {
        switch ($request) {
        case 'package.info' :
            if (!isset($this->channel)) {
                if ($this->supports() == false) {
                    return false;
                }
            }
            $r    =& $this->config->getRemote();
            $info =  $r->call('package.info', $this->ppu->packageName);
            return $info;
        }

        return false;
    }
}
?>