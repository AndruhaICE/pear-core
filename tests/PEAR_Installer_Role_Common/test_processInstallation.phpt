--TEST--
PEAR_Installer_Role_Common->processInstallation()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pf2 = new PEAR_PackageFile_v2;
$pf2->setConfig($config);
$pf2->setPackageType('php');
$pf2->setPackage('foo');
$pf2->setChannel('grob');
$pf2->setAPIStability('stable');
$pf2->setReleaseStability('stable');
$pf2->setAPIVersion('1.0.0');
$pf2->setReleaseVersion('1.0.0');
$pf2->setDate('2004-11-12');
$pf2->setDescription('foo source');
$pf2->setSummary('foo');
$pf2->setLicense('PHP License');
$pf2->setLogger($fakelog);
$pf2->clearContents();
$pf2->addFile('', 'foor.php', array('role' => 'php'));
$pf2->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$pf2->setNotes('blah');
$pf2->setPearinstallerDep('1.4.0a1');
$pf2->setPhpDep('4.2.0', '5.0.0');
$pf2->addPackageDepWithChannel('optional', 'frong', 'floop');
class Monkey extends PEAR_Installer_Role_Common
{
    function setSetupField($field, $value)
    {
        if ($value === null) {
            unset($this->_setup[$field]);
            return;
        }
        $this->_setup[$field] = $value;
    }
}

$m = new Monkey($config);
$m->setSetupField('locationconfig', false);
$phpunit->assertFalse($m->processInstallation($pf2, array(), '', ''), 'no locationconfig');
$m->setSetupField('locationconfig', 'data_dir');
$m->setSetupField('honorsbaseinstall', false);
$phpunit->showall();
$ds = DIRECTORY_SEPARATOR;
$phpunit->assertEquals(array (
  0 => $temp_path . $ds . 'data' . $ds . 'foo',
  1 => $temp_path . $ds . 'data' . $ds . 'foo' . $ds . 'path' . $ds . 'to',
  2 => $temp_path . $ds . 'data' . $ds . 'foo' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
  3 => 'smonk' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
), $m->processInstallation($pf2,
    array('role' => 'data', 'name' => 'path/to/foo.dat'), 'path/to/foo.dat', 'smonk'), 'nobaseinstall 1');
$phpunit->assertEquals(array (
  0 => $temp_path . $ds . 'data' . $ds . 'foo',
  1 => $temp_path . $ds . 'data' . $ds . 'foo' . $ds . 'path' . $ds . 'to',
  2 => $temp_path . $ds . 'data' . $ds . 'foo' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
  3 => 'smonk' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
), $m->processInstallation($pf2,
    array('role' => 'data', 'name' => 'path/to/foo.dat', 'baseinstalldir' => 'murmur'
    ), 'path/to/foo.dat', 'smonk'), 'nobaseinstall 2');
$phpunit->assertEquals(array (
  0 => $temp_path . $ds . 'data' . $ds . 'foo',
  1 => $temp_path . $ds . 'data' . $ds . 'foo',
  2 => $temp_path . $ds . 'data' . $ds . 'foo' . $ds . 'monk.php',
  3 => 'smonk' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
), $m->processInstallation($pf2,
    array('role' => 'data', 'name' => 'path/to/foo.dat', 'install-as' => 'monk.php'),
    'path/to/foo.dat', 'smonk'), 'nobaseinstall 3, install_as');

$m->setSetupField('honorsbaseinstall', true);
$phpunit->assertEquals(array (
  0 => $temp_path . $ds . 'data',
  1 => $temp_path . $ds . 'data' . $ds . 'path' . $ds . 'to',
  2 => $temp_path . $ds . 'data' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
  3 => 'smonk' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
), $m->processInstallation($pf2,
    array('role' => 'data', 'name' => 'path/to/foo.dat'), 'path/to/foo.dat', 'smonk'), 'baseinstall 1');
$phpunit->assertEquals(array (
  0 => $temp_path . $ds . 'data',
  1 => $temp_path . $ds . 'data' . $ds . 'murmur' . $ds . 'path' . $ds . 'to',
  2 => $temp_path . $ds . 'data' . $ds . 'murmur' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
  3 => 'smonk' . $ds . 'path' . $ds . 'to' . $ds . 'foo.dat',
), $m->processInstallation($pf2,
    array('role' => 'data', 'name' => 'path/to/foo.dat', 'baseinstalldir' => 'murmur'
    ), 'path/to/foo.dat', 'smonk'), 'baseinstall 2');

echo 'tests done';
?>
--EXPECT--
tests done