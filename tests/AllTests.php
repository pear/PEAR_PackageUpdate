<?php
/**
 * PEAR_PackageUpdate no-regression test suite
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
 * @since     File available since Release 1.1.0a1
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'PEAR_PackageUpdate_AllTests::main');
}

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Extensions/PhptTestSuite.php';

error_reporting(E_ALL & ~E_STRICT);
chdir(dirname(__FILE__));

require_once 'PEAR_PackageUpdate_TestSuite_Exception.php';
require_once 'PEAR_PackageUpdate_TestSuite_Stub.php';

/**
 * PEAR_PackageUpdate no-regression test suite
 *
 * Run all tests from the package root directory:
 * #phpunit PEAR_PackageUpdate_AllTests tests/AllTests.php
 * or
 * #php tests/AllTests.php
 * or for code coverage testing
 * #phpunit --coverage-html tests/coverage PEAR_PackageUpdate_AllTests tests/AllTests.php
 *
 * After the code coverage test browse the index.html file in tests/coverage.
 * The code coverage is close to 100%.
 *
 * @category  PEAR
 * @package   PEAR_PackageUpdate
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2009 Laurent Laville
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PEAR_PackageUpdate
 * @since     Class available since Release 1.1.0a1
 */

class PEAR_PackageUpdate_AllTests
{
    /**
     * Runs the test suite
     *
     * @return void
     * @static
     * @since  1.1.0a1
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Runs the test suite
     *
     * @return object the PHPUnit_Framework_TestSuite object
     * @static
     * @since  1.1.0a1
     */
    public static function suite()
    {
        $dir   = dirname(__FILE__);
        $phpt  = new PHPUnit_Extensions_PhptTestSuite($dir);
        $suite = new PHPUnit_Framework_TestSuite('PEAR_PackageUpdate Test Suite');
        $suite->addTestSuite($phpt);
        $suite->addTestSuite('PEAR_PackageUpdate_TestSuite_Exception');
        $suite->addTestSuite('PEAR_PackageUpdate_TestSuite_Stub');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'PEAR_PackageUpdate_AllTests::main') {
    PEAR_PackageUpdate_AllTests::main();
}
?>