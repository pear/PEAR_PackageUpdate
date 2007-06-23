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
    function PEAR_PackageUpdate_Null($packageName, $channel, $user_file = '', $system_file = '', $pref_file = '')
    {
        parent::PEAR_PackageUpdate($packageName, $channel, $user_file, $system_file, $pref_file);
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
$ppu =& PEAR_PackageUpdate::factory('Null', 'Log', 'peer');
if ($ppu !== false) {
    // Check for new stable version
    $ppu->setMinimumState(PEAR_PACKAGEUPDATE_STATE_STABLE);
    $ppu->setMinimumReleaseType(PEAR_PACKAGEUPDATE_TYPE_BUG);
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

// Check for errors.
if ($ppu->hasErrors()) {
    $error = $ppu->popError();
    echo "<b>Error occured when trying to update: PEAR::Log package</b> <br />\n";
    echo "<b>Message:</b> " . $error['message'] ."<br />\n";
    echo "<hr /><i>Context:</i><br />\n";
    echo "<b>File:</b> " . $error['context']['file'] ."<br />\n";
    echo "<b>Line:</b> " . $error['context']['line'] ."<br />\n";
    echo "<b>Function:</b> " . $error['context']['function'] ."<br />\n";
    echo "<b>Class:</b> " . $error['context']['class'] ."<br />\n";
    exit();
}

// your application code goes here ...
print 'Hello World';
?>