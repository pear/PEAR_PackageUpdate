--TEST--
PEAR_PackageUpdate using custom configuration
--FILE--
<?php
$ds         = DIRECTORY_SEPARATOR;
$dir        = dirname(__FILE__);
$sysconfdir = $dir . $ds . 'sysconf_dir';
$peardir    = $dir . $ds . 'pear_dir';

putenv("PHP_PEAR_SYSCONF_DIR=" . $sysconfdir);
set_include_path($peardir . PATH_SEPARATOR . get_include_path());

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
 * Test invalid driver name (misspell or does not exist)
 *
 * Will use the pear config files into the default system directory
 * (PEAR_CONFIG_SYSCONFDIR).
 */
$testCase = 'testWrongDriver';

$ppu =& PEAR_PackageUpdate::factory('Null', 'Text_Diff', 'pear');

$result = ($ppu === false) ? 'KO' : 'OK';

echo $testCase . ' initClass : ' . $result;
echo "\n";

/**
 * TestCase 2:
 * Test invalid driver name (class name is not corresponding to path file)
 *
 * Will use the pear config files into the default system directory
 * (PEAR_CONFIG_SYSCONFDIR).
 */
$testCase = 'testWrongDriverClass';

$ppu =& PEAR_PackageUpdate::factory('Foo', 'Text_Diff', 'pear');

$result = ($ppu === false) ? 'KO' : 'OK';

echo $testCase . ' initClass : ' . $result;
echo "\n";

/**
 * TestCase 3:
 * Test config files that does not exist
 */
$testCase = 'testWarningConfigFiles';

$ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear',
                                    $user_file . '.wrong', $system_file . '.wrong');

$result = 'KO';

if ($ppu !== false && $ppu->hasErrors()) {
    $e = $ppu->popError();
    if ($e['code'] == PEAR_PACKAGEUPDATE_ERROR_INVALIDINIFILE &&
        strpos($e['message'], 'pear-system') > 0
    ) {
        $result = 'System configuration file provided does not exist';
    }
    if ($ppu->hasErrors()) {
        $e = $ppu->popError();
        if ($e['code'] == PEAR_PACKAGEUPDATE_ERROR_INVALIDINIFILE &&
            strpos($e['message'], 'pear-user') > 0
        ) {
            $result = 'All configuration files provided does not exist';
        }
    }
}
echo $testCase . ' initClass : ' . $result;
echo "\n";

/**
 * TestCase 4:
 * Test a preference PPU file that does not exist
 *
 * Must use the pear config files into the default system directory
 * (PEAR_CONFIG_SYSCONFDIR).
 */
$testCase = 'testWrongPrefFile';

$ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear',
                                    '', '', $pearcfgdir . $ds . 'wrong_' . basename($pref_file));

$result = '??';

if ($ppu !== false && $ppu->hasErrors()) {
    $e = $ppu->popError();
    if ($e['code'] == PEAR_PACKAGEUPDATE_ERROR_INVALIDINIFILE &&
        strpos($e['message'], 'ppu-pref') > 0
    ) {
        $result = 'Preference configuration file does not exist';
    }
}
echo $testCase . ' initClass : ' . $result;
echo "\n";

/**
 * TestCase 5:
 * Test a corrupted preference PPU file (wrong content)
 *
 * Will use the pear config files into the default system directory
 * (PEAR_CONFIG_SYSCONFDIR).
 */
$testCase = 'testCorruptedPrefFile';

$bad_pref_file = $pearcfgdir . $ds . 'corrupted_' . basename($pref_file);
file_put_contents($bad_pref_file, '???');

$ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear',
                                    '', '', $bad_pref_file);

$result = '??';

if ($ppu !== false && $ppu->hasErrors()) {
    $e = $ppu->popError();
    if ($e['code'] == PEAR_PACKAGEUPDATE_ERROR_PREFFILE_CORRUPTED) {
        $result = 'Preference configuration file is corrupted';
    }
}
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
$bad_pref_file = $pearcfgdir . $ds . 'corrupted_' . basename($pref_file);

unlink ($system_file);
unlink ($pref_file);
unlink ($bad_pref_file);
?>
--EXPECT--
testWrongDriver initClass : KO
testWrongDriverClass initClass : KO
testWarningConfigFiles initClass : All configuration files provided does not exist
testWrongPrefFile initClass : Preference configuration file does not exist
testCorruptedPrefFile initClass : Preference configuration file is corrupted
