--TEST--
PEAR_PackageUpdate using default configuration
--FILE--
<?php
$ds         = DIRECTORY_SEPARATOR;
$dir        = dirname(__FILE__);
$sysconfdir = $dir . $ds . 'sysconf_dir';
$peardir    = $dir . $ds . 'pear_dir';

putenv("PHP_PEAR_SYSCONF_DIR=" . $sysconfdir);

// we get PEAR_PackageUpdate class only here due to setting of PEAR_CONFIG_SYSCONFDIR
include_once 'PEAR/PackageUpdate.php';

$config =& PEAR_Config::singleton();
$cfgDir = $config->get('cfg_dir');  // available only since PEAR 1.7.0

if (!is_null($cfgDir) && is_dir($cfgDir)) {
    $pearcfgdir = $peardir . $ds . 'cfg';
} else {
    $pearcfgdir = $sysconfdir;
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $system_file = $sysconfdir . $ds . 'pearsys.ini';
    $pref_file   = $pearcfgdir . $ds . 'ppurc.ini';
} else {
    $system_file = $sysconfdir . $ds . 'pear.conf';
    $pref_file   = $pearcfgdir . $ds . '.ppurc';
}
$user_file = '';

if (!file_exists($system_file)) {
    // write once PEAR system-wide config file for simulation
    $config->set('php_dir', $peardir);
    if (!is_null($cfgDir)) {
        // only for PEAR 1.7.0 or greater
        $config->set('cfg_dir', $pearcfgdir);
    }
    $config->writeConfigFile($system_file);
}

/**
 * TestCase 1:
 * default class constructor without parameter
 *
 * Must use the pear config files into the default system directory
 * (PEAR_CONFIG_SYSCONFDIR).
 */
$testCase = 'testConfigFilesExistInSysConfDir';

$ppu =& PEAR_PackageUpdate::factory('Cli', 'text_diff', 'pear');

$result = ($ppu !== false)
    ? 'OK' : 'System or Preference configuration file does not exist';

echo $testCase . ' initClass : ' . $result;
?>
--CLEAN--
<?php
$ds         = DIRECTORY_SEPARATOR;
$dir        = dirname(__FILE__);
$sysconfdir = $dir . $ds . 'sysconf_dir';
$peardir    = $dir . $ds . 'pear_dir';

putenv("PHP_PEAR_SYSCONF_DIR=" . $sysconfdir);
chdir($dir);

include_once 'PEAR/Config.php';

$config =& PEAR_Config::singleton();
$cfgDir = $config->get('cfg_dir');  // available only since PEAR 1.7.0

if (!is_null($cfgDir) && is_dir($cfgDir)) {
    $pearcfgdir = $cfgDir;
} else {
    $pearcfgdir = $sysconfdir;
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $system_file = $sysconfdir . $ds . 'pearsys.ini';
    $pref_file   = $pearcfgdir . $ds . 'ppurc.ini';
} else {
    $system_file = $sysconfdir . $ds . 'pear.conf';
    $pref_file   = $pearcfgdir . $ds . '.ppurc';
}

if (file_exists($system_file)) {
    unlink ($system_file);
}
if (file_exists($pref_file)) {
    unlink ($pref_file);
}
?>
--EXPECT--
testConfigFilesExistInSysConfDir initClass : OK
