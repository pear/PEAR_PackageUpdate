<?php
/**
 * PEAR_PackageUpdate Package Script Generator
 *
 * Generate a new fresh version of package xml 2.0
 * built with PEAR_PackageFileManager 1.6.0+
 *
 * PHP versions 4 and 5
 *
 * @category  PEAR
 * @package   PEAR_PackageUpdate
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2007-2009 Laurent Laville
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/PEAR_PackageUpdate
 * @since     File available since Release 0.6.0
 * @ignore
 */

require_once 'PEAR/PackageFileManager2.php';

PEAR::setErrorHandling(PEAR_ERROR_DIE);

chdir(dirname(__FILE__));
$packagefile = 'package2.xml';

$options = array('filelistgenerator' => 'file',
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
$p2->setReleaseVersion('1.1.0RC1');
$p2->setAPIVersion('1.1.0');
$p2->setReleaseStability('beta');
$p2->setAPIStability('stable');
$p2->setNotes('
[+] New features
none

[*] Improvements / changes
- License changed from PHP 3.01 to new BSD

[-] Bugfixes
none

[!] Quality Assurance
- Make test suite more easy: used the PhptTestSuite extension of PHPUnit3 Framework
');
$p2->addInstallAs('Cli.php', 'PackageUpdate/Cli.php');
$p2->generateContents();

//$p2->setPearinstallerDep('1.5.4');
/*$p2->updateMaintainer('lead', 'scottmattocks',
                      'Scott Mattocks', 'scottmattocks@php.net', 'no'); */

if (isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $p2->writePackageFile();
} else {
    $p2->debugPackageFile();
}
?>