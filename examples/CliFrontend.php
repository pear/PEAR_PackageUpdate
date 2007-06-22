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

$ppu =& PEAR_PackageUpdate::factory('Cli', $packageName, $channel);
if ($ppu !== false) {
    $ppu->setMinimumState(PEAR_PACKAGEUPDATE_STATE_STABLE);
    $ppu->setMinimumReleaseType(PEAR_PACKAGEUPDATE_TYPE_BUG);
    // Check for new stable version
    if ($ppu->checkUpdate()) {

        $rel = $ppu->getLatestRelease();
        $vers = $rel['version'] . ' (' . $rel['state'] . ')';
        print "A new version $vers of package $channel/$packageName is available \n";

        // Update your local copy
        $upd = $ppu->update();
    }

    if (isset($upd) && $upd === true) {
        print "Your local copy is now up-to-date";
    } else {
        $inst = $ppu->getInstalledRelease();
        if (is_array($inst)) {
            //
            $vers = $inst['version'] . ' (' . $inst['state'] . ')';
            print "You are still using version $vers of package $channel/$packageName \n";
            print "which depend on packages : \n";
            foreach ($inst['deps'] as $dep) {
                if ($dep['type'] === 'pkg') {
                    print "- " . $dep['channel'] .'/'. $dep['name'] . "\n";
                }
            }
        } else {
            print "Package $channel/$packageName is not installed \n";
        }
    }
}

// your application code goes here ...
?>