<?php
/**
 * This is the default CLI driver for PEAR_PackageUpdate.
 *
 * PHP versions 4 and 5
 *
 * @category PEAR
 * @package  PEAR_PackageUpdate
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    File available since Release 0.6.0
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
 * @category PEAR
 * @package  PEAR_PackageUpdate
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    Class available since Release 0.6.0
 */

class PEAR_PackageUpdate_Cli extends PEAR_PackageUpdate
{
    /**
     * Cli driver class constructor
     *
     * @param string $packageName The package to update.
     * @param string $channel     The channel the package resides on.
     * @param string $user_file   (optional) file to read PEAR user-defined
     *                            options from
     * @param string $system_file (optional) file to read PEAR system-wide
     *                            defaults from
     * @param string $pref_file   (optional) file to read PPU user-defined
     *                            options from
     *
     * @access public
     * @return void
     * @since  0.6.0
     */
    function PEAR_PackageUpdate_Cli($packageName, $channel,
        $user_file = '', $system_file = '', $pref_file = '')
    {
        parent::PEAR_PackageUpdate($packageName, $channel,
            $user_file, $system_file, $pref_file);
    }

    /**
     * Cli driver does not redirects or exits
     * to force the user to restart the application.
     *
     * @access public
     * @return void
     * @since  0.6.0
     */
    function forceRestart()
    {
    }

    /**
     * Cli driver does not present any frontend when an update is needed
     *
     * @access public
     * @return boolean Always true
     * @since  0.6.0
     */
    function presentUpdate()
    {
        // always update the package to latest version depending of options
        // see    setMinimumState(), setMinimumReleaseType()
        return true;
    }
}
?>