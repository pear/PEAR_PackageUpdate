<?php
/**
 * Test suite for API invalid parameter call on advmultiselect element
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
 * @copyright 2009 Laurent Laville
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: $Id$
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    File available since Release 1.1.0
 */

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'PEAR.php';
require_once 'PEAR/PackageUpdate.php';

/**
 * Test suite class to test API Exceptions
 *
 * @category  PEAR
 * @package   PEAR_PackageUpdate
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2009 Laurent Laville
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  Release: @package_version@
 * @link     http://pear.php.net/package/PEAR_PackageUpdate
 * @since    Class available since Release 1.1.0
 */
class PEAR_PackageUpdate_TestSuite_Exception extends PHPUnit_Framework_TestCase
{
    /**
     * file to read PEAR user-defined options from
     * @var string
     */
    protected $usr_file;

    /**
     * file to read system-wide defaults from
     * @var string
     */
    protected $sys_file;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $ds         = DIRECTORY_SEPARATOR;
        $dir        = dirname(__FILE__);
        $sysconfdir = $dir . $ds . 'sysconf_dir';
        $peardir    = $dir . $ds . 'pear_dir';
        $cachedir   = $peardir . $ds . 'cache';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->usr_file = $sysconfdir . $ds . 'pear.ini';
            $this->sys_file = $sysconfdir . $ds . 'pearsys.ini';
        } else {
            $this->usr_file = $sysconfdir . $ds . '.pearrc';
            $this->sys_file = $sysconfdir . $ds . 'pear.conf';
        }

        putenv("PHP_PEAR_SYSCONF_DIR=" . $sysconfdir);
        include_once 'PEAR/PackageUpdate.php';

        $config =& PEAR_Config::singleton($this->usr_file, $this->sys_file);
        $config->set('php_dir', $peardir);
        $config->set('cache_dir', $cachedir);
        $config->set('cache_ttl', 3600);
        $config->writeConfigFile($this->usr_file);
    }

    /**
     * Tears down the fixture.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        unlink($this->usr_file);
    }

    /**
     * tests API throws error
     *
     * @param array  $error PEAR_ErrorStack stack entry
     * @param int    $code  error code
     * @param string $level error level (exception or error)
     *
     * @return void
     */
    public function catchError($error, $code = null, $level = null)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $error);
        if (isset($code)) {
            $this->assertEquals($error['code'], $code);
        }
        if (isset($level)) {
            $this->assertEquals($error['level'], $level);
        }
    }

    /**
     * Tests to catch exception for package name forgotten
     * when getting the latest release available
     *
     * @return void
     */
    public function testGetLatestReleaseOnInvalidPackageName()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', '', 'pear');

        if ($ppu !== false) {
            $ppu->getLatestRelease();
            $r = $ppu->hasErrors('error');
            if ($r = $r > 0) {
                $e = $ppu->popError();
                $this->catchError($e, PEAR_PACKAGEUPDATE_ERROR_NOPACKAGE, 'error');
            }
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests to catch exception for channel name forgotten
     * when getting the latest release available
     *
     * @return void
     */
    public function testGetLatestReleaseOnInvalidChannelName()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', '');

        if ($ppu !== false) {
            $ppu->getLatestRelease();
            $r = $ppu->hasErrors('error');
            if ($r = $r > 0) {
                $e = $ppu->popError();
                $this->catchError($e, PEAR_PACKAGEUPDATE_ERROR_NOCHANNEL, 'error');
            }
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }
}
?>