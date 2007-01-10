<?php
/*
 * This is the default CLI driver for PEAR_PackageUpdate.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PEAR
 * @package    PEAR_PackageUpdate
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    CVS: $Id$
 * @since      File available since Release 0.6.0
 */


/**
 * This is the default CLI driver for PEAR_PackageUpdate.
 *
 * A package to make adding self updating functionality to other
 * packages easy.
 *
 * EXAMPLE:
 * <code>
 * <?php
 *     require_once 'PEAR/PackageUpdate.php';
 *     // check for updates of PEAR::Config package
 *     $ppu =& PEAR_PackageUpdate::factory('Cli', 'Config', 'pear');
 *     if ($ppu !== false) {
 *         // Check for new stable and minor changes version
 *         $ppu->setMinimumState(PEAR_PACKAGEUPDATE_STATE_STABLE);
 *         $ppu->setMinimumReleaseType(PEAR_PACKAGEUPDATE_TYPE_MINOR);
 *         if ($ppu->checkUpdate()) {
 *             // Update your local copy
 *             if ($ppu->update()) {
 *                 include_once 'Config.php';
 *             }
 *         }
 *     }
 * ?>
 * </code>
 *
 * @category   PEAR
 * @package    PEAR_PackageUpdate
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @since      Class available since Release 0.6.0
 */

class PEAR_PackageUpdate_Cli extends PEAR_PackageUpdate
{
    function PEAR_PackageUpdate_Cli($packageName, $channel)
    {
        parent::PEAR_PackageUpdate($packageName, $channel);
    }

    function forceRestart()
    {
    }

    function presentUpdate()
    {
        // always update the package to latest version depending of options
        // see    setMinimumState(), setMinimumReleaseType()
        return true;
    }
}
?>