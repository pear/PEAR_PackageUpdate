<?php
/**
 * Always keep your application up-to-date with the most recent and stable version
 * of PEAR::Log package.
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    PEAR_PackageUpdate
 * @access     public
 * @since      File available since Release 0.5.0
 */

require_once 'Log.php';
require_once 'PEAR/PackageUpdate.php';

/**
 * This class allow to use PEAR_PackageUpdate as backend without any frontend.
 * No end-user action needed.
 * @ignore
 */
class PEAR_PackageUpdate_Null extends PEAR_PackageUpdate
{
    function PEAR_PackageUpdate_Null($packageName, $channel)
    {
        parent::PEAR_PackageUpdate($packageName, $channel);
        $this->setMinimumState(PEAR_PACKAGEUPDATE_STATE_STABLE);
        $this->setMinimumReleaseType(PEAR_PACKAGEUPDATE_TYPE_BUG);
    }

    function forceRestart()
    {
        // removes warning message given by pear installer
        ob_end_clean();
        // Reload current page.
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Check for updates of PEAR::Log package though pear.php.net channel
$ppu =& PEAR_PackageUpdate::factory('Null', 'Log', 'pear');
if ($ppu !== false) {
    // Check for new stable version
    if ($ppu->checkUpdate()) {
        // Update your local copy
        ob_start();
        if ($ppu->update()) {
            // If the update succeeded, the application should be restarted.
            $ppu->forceRestart();
        }
        ob_end_clean();
    }
}

// your application code goes here ...
print 'Hello World';
?>