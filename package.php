<?php
/**
 * PEAR_PackageUpdate Package Script Generator
 *
 * Generate a new fresh version of package xml 2.0 built with PEAR_PackageFileManager 1.6.0+
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PEAR
 * @package    PEAR_PackageUpdate
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2007 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR_PackageUpdate
 * @since      File available since Release 0.6.0
 * @ignore
 */

require_once 'PEAR/PackageFileManager2.php';

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagefile = 'c:/php/pear/PEAR_PackageUpdate/package2.xml';

$options = array('filelistgenerator' => 'cvs',
    'packagefile' => 'package2.xml',
    'baseinstalldir' => 'PEAR',
    'simpleoutput' => true,
    'clearcontents' => false,
    'changelogoldtonew' => false,
    'ignore' => array('package.php')
    );

$p2 = &PEAR_PackageFileManager2::importOptions($packagefile, $options);
$p2->setPackageType('php');
$p2->addRelease();
$p2->setReleaseVersion('0.7.0');
$p2->setAPIVersion('1.0.0');
$p2->setReleaseStability('beta');
$p2->setAPIStability('stable');
$p2->setNotes('* bugs
- fix bug #11384: Undefined variable
  This solved also the problem to PEAR non standard installation,
  by giving file to read PEAR user-defined options from
  and/or file to read PEAR system-wide defaults from

* news
- add (missing) snapshot package state

* changes
- examples/CliFrontend.php script was changed a bit to prevent notice error if package is not installed
- add myself (Laurent Laville) as co-author, in header comment blocks.
- add credit, in header comment blocks, to Ian Eure for his function (example)
  to repackage PEAR_Errors for use with ErrorStack
');
$p2->addInstallAs('Cli.php', 'PackageUpdate/Cli.php');
$p2->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $p2->writePackageFile();
} else {
    $p2->debugPackageFile();
}
?>