<?php
/**
 * PEAR_PackageUpdate Package Script Generator
 *
 * Generate a new fresh version of package xml 2.0
 * built with PEAR_PackageFileManager 1.6.0+
 *
 * PHP versions 4 and 5
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
 * @copyright 2007-2008 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/PEAR_PackageUpdate
 * @since     File available since Release 0.6.0
 * @ignore
 */

require_once 'PEAR/PackageFileManager2.php';

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagefile = 'c:/php/pear/PEAR_PackageUpdate/package2.xml';

$options = array('filelistgenerator' => 'cvs',
    'packagefile' => 'package2.xml',
    'baseinstalldir' => 'PEAR',
    'addhiddenfiles' => true,
    'simpleoutput' => true,
    'clearcontents' => false,
    'changelogoldtonew' => false,
    'ignore' => array(__FILE__)
    );

$p2 = &PEAR_PackageFileManager2::importOptions($packagefile, $options);
$p2->setPackageType('php');
$p2->addRelease();
$p2->setReleaseVersion('1.0.0');
$p2->setAPIVersion('1.0.0');
$p2->setReleaseStability('stable');
$p2->setAPIStability('stable');
$p2->setNotes('FINAL and first STABLE version after 9 month since last BETA release.
No bug found !

* news
- default configuration file (.ppurc|ppurc.ini) is loaded from and saved into PEAR
configuration directory (PEAR 1.7.0+ cfg_dir directive) if available.

* QA
- require now at least PEAR installer 1.5.4 rather than 1.4.8
(security vulnerability fixes)
- Scott Mattocks is marked as inactive on his request, because he did not have
time to give attention to this package it deserve. Thanks Scott for your past works
on this package, and make this cool features a reality for the community.
- Test Suite used now the .phpt test case rather than phpunit, just in case of a
crash, and recovery of PEAR configuration (suggestion given by Christian Weiske
already in same condition for PEAR_Info).
');
$p2->addInstallAs('Cli.php', 'PackageUpdate/Cli.php');
$p2->generateContents();

$p2->setPearinstallerDep('1.5.4');
$p2->updateMaintainer('lead', 'scottmattocks',
                      'Scott Mattocks', 'scottmattocks@php.net', 'no');

if (isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $p2->writePackageFile();
} else {
    $p2->debugPackageFile();
}
?>