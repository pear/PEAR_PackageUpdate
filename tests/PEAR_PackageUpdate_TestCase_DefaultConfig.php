<?php
/**
 * PEAR_PackageUpdate unit test case for default configuration files usage.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  PEAR
 * @package   PEAR_PackageUpdate
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2007 Laurent Laville
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/PEAR_PackageUpdate
 * @since     File available since Release 1.0.0
 */

if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "PEAR_PackageUpdate_TestCase_DefaultConfig::main");
}

require_once 'PHPUnit/Framework.php';

/**
 * Unit test case for PEAR_PackageUpdate default configuration files usage.
 *
 * @category  PEAR
 * @package   PEAR_PackageUpdate
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2007 Laurent Laville
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: $Id$
 * @link      http://pear.php.net/package/PEAR_PackageUpdate
 * @since     Class available since Release 1.0.0
 */

class PEAR_PackageUpdate_TestCase_DefaultConfig extends PHPUnit_Framework_TestCase
{
    /**
     * Saves content of PHP_PEAR_SYSCONF_DIR environment variable
     *
     * @var    string
     * @since  1.0.0
     */
    private $sysconfdir;

    /**
     * Preference PPU filename
     *
     * @var    string
     * @since  1.0.0
     */
    private $pref_file;

    /**
     * Runs the test methods of this class.
     *
     * @return void
     * @static
     * @since  1.0.0
     */
    public static function main()
    {
        include_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("PEAR_PackageUpdate " .
            "test default configuration");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @since  1.0.0
     */
    protected function setUp()
    {
        chdir(dirname(__FILE__));

        $this->sysconfdir = getenv('PHP_PEAR_SYSCONF_DIR');
        $sysconfdir       = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sysconf_dir';
        putenv("PHP_PEAR_SYSCONF_DIR=" . $sysconfdir);

        /**
         * we get PEAR_PackageUpdate class only here
         * due to setting of PEAR_CONFIG_SYSCONFDIR
         */
        include_once '..' . DIRECTORY_SEPARATOR . 'PackageUpdate.php';

        if (OS_WINDOWS) {
            $conf_file       = $sysconfdir . DIRECTORY_SEPARATOR . 'pearsys.ini';
            $this->pref_file = $sysconfdir . DIRECTORY_SEPARATOR . 'ppurc.ini';
        } else {
            $conf_file       = $sysconfdir . DIRECTORY_SEPARATOR . 'pear.conf';
            $this->pref_file = $sysconfdir . DIRECTORY_SEPARATOR . '.ppurc';
        }

        if (!file_exists($conf_file)) {
            $config =& PEAR_Config::singleton();
            $config->set('php_dir', dirname(__FILE__) . DIRECTORY_SEPARATOR .
                'pear_dir');
            $config->writeConfigFile($conf_file);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     * @since  1.0.0
     */
    protected function tearDown()
    {
        putenv("PHP_PEAR_SYSCONF_DIR=" . $this->sysconfdir);
    }

    /**
     * Test invalid driver name (misspell or does not exist)
     *
     * Will use the pear config files into the default system directory
     * (PEAR_CONFIG_SYSCONFDIR).
     *
     * @return void
     * @since  1.0.0
     */
    public function testWrongDriver()
    {
        $ppu =& PEAR_PackageUpdate::factory('Null', 'Console_Getopt', 'pear');
        $this->assertFalse($ppu instanceof PEAR_PackageUpdate,
            'Null driver exist in the include path');
    }

    /**
     * Test a preference PPU file that does not exist
     *
     * Will use the pear config files into the default system directory
     * (PEAR_CONFIG_SYSCONFDIR).
     *
     * @return void
     * @since  1.0.0
     */
    public function testWrongPrefFile()
    {
        $pref_file = dirname($this->pref_file) . DIRECTORY_SEPARATOR .
                         'wrong.ppurc.ini';

        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Console_Getopt', 'pear',
                  '', '', $pref_file);
        if ($ppu !== false) {
            $e = $ppu->popError();
            $this->assertTrue(($e['code']
                    == PEAR_PACKAGEUPDATE_ERROR_INVALIDINIFILE)
                && ($e['params']['layer'] == 'ppu-pref'));
        }
    }

    /**
     * Test a corrupted preference PPU file (wrong content)
     *
     * Will use the pear config files into the default system directory
     * (PEAR_CONFIG_SYSCONFDIR).
     *
     * @return void
     * @since  1.0.0
     */
    public function testCorruptedPrefFile()
    {
        $pref_file = dirname($this->pref_file) . DIRECTORY_SEPARATOR .
                         'corrupted.ppurc.ini';
        if (!file_exists($pref_file)) {
            // creates corrupted content only once
            file_put_contents($pref_file, '???');
        }
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Console_Getopt', 'pear',
                  '', '', $pref_file);
        if ($ppu !== false) {
            $e = $ppu->popError();
            $this->assertTrue($e['code']
                == PEAR_PACKAGEUPDATE_ERROR_PREFFILE_CORRUPTED);
        }
    }
}

/**
 * Call PEAR_PackageUpdate_TestCase_DefaultConfig::main()
 * if this source file is executed directly.
 */
if (PHPUnit_MAIN_METHOD == "PEAR_PackageUpdate_TestCase_DefaultConfig::main") {
    PEAR_PackageUpdate_TestCase_DefaultConfig::main();
}
?>