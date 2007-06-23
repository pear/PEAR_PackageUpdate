<?php
/**
 * Always keep your application up-to-date with the most recent and stable version
 * of PEAR::Config package.
 * Present new features of PPU version 0.6.0 : get more details
 * on package installed and available
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    PEAR_PackageUpdate
 * @access     public
 * @since      File available since Release 0.6.0
 */

require_once 'PEAR/PackageUpdate.php';

// Check for updates of PEAR::Config package though pear.php.net channel
$channel     = 'pear';
$packageName = 'Config';

$ppu =& PEAR_PackageUpdate::factory('Cli', $packageName, $channel, 'c:\wamp\php\pear.ini', '', 'c:\wamp\php\ppurc.ini');
if ($ppu !== false) {
    $ppu->setMinimumState(PEAR_PACKAGEUPDATE_STATE_STABLE);
    $ppu->setMinimumReleaseType(PEAR_PACKAGEUPDATE_TYPE_BUG);
    // Check for new stable version
    if ($ppu->checkUpdate()) {

        $inst = $ppu->getInstalledRelease();
        if (is_array($inst)) {
            $rel = $ppu->getLatestRelease();
            $vers = $rel['version'] . ' (' . $rel['state'] . ')';
            print "A new version $vers of package $channel/$packageName is available \n";

            // Update your local copy, only if package is already installed
            $upd = $ppu->update();
            if ($ppu->hasErrors()) {
                $error = $ppu->popError();
                echo "Error occured when trying to update: $channel/$packageName package\n";
                echo "Message: " . $error['message'] ."\n";
                echo "*** Context: ***\n";
                echo "File: " . $error['context']['file'] ."\n";
                echo "Line: " . $error['context']['line'] ."\n";
                echo "Function: " . $error['context']['function'] ."\n";
                echo "Class: " . $error['context']['class'] ."\n";
                exit();
            }
        } else {
            print "Package $channel/$packageName is not installed \n";
        }

    }

    if (isset($upd) && $upd === true) {
        print "Your local copy is now up-to-date";
    } else {
        $inst = $ppu->getInstalledRelease();
        if (is_array($inst)) {
            $vers = $inst['version'] . ' (' . $inst['state'] . ')';
            print "You are still using version $vers of package $channel/$packageName \n";
            if (is_array($inst['deps'])) {
                print "which depend on package(s) : \n";
                foreach ($inst['deps'] as $dep) {
                    if ($dep['type'] === 'pkg') {
                        print "- " . $dep['channel'] .'/'. $dep['name'] . "\n";
                    }
                }
            }
        }
    }
}

// your application code goes here ...
?>