<?php
/**
 * Test suite for remote access in the PEAR_PackageUpdate class
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
 * @link      http://pear.php.net/package/PEAR_PackageUpdate
 * @since     File available since Release 1.1.0
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PEAR/REST.php';

/**
 * Test suite for remote access to PEAR repository
 *
 * @category  PEAR
 * @package   PEAR_PackageUpdate
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2009 Laurent Laville
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PEAR_PackageUpdate
 * @since     Class available since Release 1.1.0
 */

class PEAR_PackageUpdate_TestSuite_Stub extends PHPUnit_Framework_TestCase
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
     * file to read package update options from
     * @var string
     */
    protected $cfg_file;

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
        $confdir    = $peardir . $ds . 'cfg';
        $cachedir   = $peardir . $ds . 'cache';
        $restdir    = $peardir . $ds . 'rest_resource';
        $baseurl    = 'http://pear.php.net/rest/';

        putenv("PHP_PEAR_SYSCONF_DIR=" . $sysconfdir);
        include_once 'PEAR/PackageUpdate.php';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->usr_file = $sysconfdir . $ds . 'pear.ini';
            $this->sys_file = $sysconfdir . $ds . 'pearsys.ini';
            $this->cfg_file = $confdir    . $ds . 'ppurc.ini';
        } else {
            $this->usr_file = $sysconfdir . $ds . '.pearrc';
            $this->sys_file = $sysconfdir . $ds . 'pear.conf';
            $this->cfg_file = $confdir    . $ds . '.ppurc';
        }

        $config =& PEAR_Config::singleton($this->usr_file, $this->sys_file);
        $config->set('cfg_dir', $confdir);
        $config->set('php_dir', $peardir);
        $config->set('cache_dir', $cachedir);
        $config->set('cache_ttl', 3600);
        $config->writeConfigFile($this->usr_file);

        $rest = new PEAR_REST($config);

        /*
         Save the remote REST resources to local cache :
         - to suggest an update available for installed package (xml 1.0)
           Text_Diff version 0.2.1
         - to suggest no update available for installed package (xml 2.0)
           Console_Getopt version 1.2.3
         */
        $lastmodified = time();
        $packages     = array('text_diff', 'console_getopt', 'services_w3c_cssvalidator');

        foreach ($packages as $p) {
            // all releases for package
            $contents = file_get_contents($restdir . $ds .
                            'rest.cachefile.'.$p.'.allreleases.ser');
            $releases = unserialize($contents);
            if (!isset($releases['r'][0])) {
                $releases['r'] = array($releases['r']);
            }

            // prepare cache file (+id) about each :
            foreach ($releases['r'] as $r) {
                // package info for version
                $contents = file_get_contents($restdir . $ds .
                                'rest.cachefile.'.$p.'.'.$r['v'].'.ser');
                $contents = unserialize($contents);
                $url      = $baseurl . 'r/'.$p.'/'.$r['v'].'.xml';
                $rest->saveCache($url, $contents, $lastmodified);

                // depencencies for version
                $contents = file_get_contents($restdir . $ds .
                                'rest.cachefile.'.$p.'.deps.'.$r['v'].'.ser');
                $contents = unserialize($contents);
                $url      = $baseurl . 'r/'.$p.'/deps.'.$r['v'].'.txt';
                $rest->saveCache($url, $contents, $lastmodified);
            }
            // general package information
            $contents = file_get_contents($restdir . $ds .
                            'rest.cachefile.'.$p.'.info.ser');
            $contents = unserialize($contents);
            $url      = $baseurl . 'p/'.$p.'/info.xml';
            $rest->saveCache($url, $contents, $lastmodified);

            // all releases
            $url      = $baseurl . 'r/'.$p.'/allreleases.xml';
            $rest->saveCache($url, $releases, $lastmodified);
        }
    }

    /**
     * Tears down the fixture.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        if (file_exists($this->cfg_file)) {
            unlink($this->cfg_file);
        }
        unlink($this->usr_file);
    }

    /**
     * Tests for checking if an update is available for a package installed
     * (in xml version 1.0)
     *
     * @return void
     * @group  stub
     */
    public function testCheckUpdateAvailableForPackageXml1()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear',
                                            $this->usr_file, $this->sys_file);

        if ($ppu !== false) {
            $r = $ppu->checkUpdate();
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests for checking if no update available for a package installed
     * (in xml version 2.0)
     *
     * @return void
     * @group  stub
     */
    public function testCheckUpdateNotAvailableForPackageXml2()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Console_Getopt', 'pear');

        if ($ppu !== false) {
            $r = ($ppu->checkUpdate() == false);
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests for checking if an update is available for a package installed
     * and user does not want to upgrade
     *
     * @return void
     * @group  stub
     */
    public function testCheckUpdateAvailableButUserRefuse()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear');
        /*
          even if an update is available, user does not want to upgrade
          to any other version
         */
        $ppu->setDontAskAgain(true);

        if ($ppu !== false) {
            $r = ($ppu->checkUpdate() == false);
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests for checking if an update is available for a package not yet installed
     *
     * @return void
     * @group  stub
     */
    public function testCheckUpdateForPackageNotInstalled()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Services_W3C_CSSValidator', 'pear');

        if ($ppu !== false) {
            $r = $ppu->checkUpdate();
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests to install only newer version that follow a major release
     *
     * @return void
     * @group  stub
     */
    public function testUpdateOnNextRelease()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear');

        if ($ppu !== false) {
            $r = $ppu->checkUpdate();
            if ($r) {
                $releaseInfo = $ppu->getLatestRelease();

                if (version_compare($releaseInfo['version'], '1.1.0', 'eq')) {
                    // user have an old version installed (0.2.1)
                    // but want to wait for next release after 1.1.0
                    $ppu->setDontAskUntilNextRelease(true);

                    $r = ($ppu->checkUpdate() == false);
                }
            }
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests to install only newer and stable version
     *
     * @return void
     * @group  stub
     */
    public function testUpdateOnlyStableVersion()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Services_W3C_CSSValidator', 'pear');

        if ($ppu !== false) {
            $ppu->setMinimumState(PEAR_PACKAGEUPDATE_STATE_STABLE);
            $r = ($ppu->checkUpdate() == false);
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests to install only newer and major version
     *
     * @return void
     * @group  stub
     */
    public function testUpdateOnlyMajorVersion()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear');

        if ($ppu !== false) {
            $ppu->setMinimumReleaseType(PEAR_PACKAGEUPDATE_TYPE_MAJOR);
            $r = $ppu->checkUpdate();
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }

    /**
     * Tests to get the latest version available for a package on remote PEAR database
     *
     * @return void
     * @group  stub
     */
    public function testGetLatestReleaseAvailable()
    {
        $ppu =& PEAR_PackageUpdate::factory('Cli', 'Text_Diff', 'pear');

        if ($ppu !== false) {
            $r = $ppu->getLatestRelease();
            if ($r !== false) {
                $this->assertEquals($r['version'], '1.1.0');
                return;
            }
        } else {
            $r = $ppu;
        }
        $this->assertTrue($r);
    }
}
?>