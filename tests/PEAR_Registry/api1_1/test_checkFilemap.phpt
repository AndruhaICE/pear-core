--TEST--
PEAR_Registry->checkFileMap() (API v1.1)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
require_once 'PEAR/Registry.php';
$pv = phpversion() . '';
$av = $pv{0} == '4' ? 'apiversion' : 'apiVersion';
if (!in_array($av, get_class_methods('PEAR_Registry'))) {
    echo 'skip';
}
if (PEAR_Registry::apiVersion() != '1.1') {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pf = new PEAR_PackageFile_v1;
$pf->setConfig($config);
$pf->setSummary('sum');
$pf->setDescription('desc');
$pf->setLicense('PHP License');
$pf->setVersion('1.0.0');
$pf->setState('stable');
$pf->setDate('2004-11-17');
$pf->setNotes('sum');
$pf->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$pf->addFile('', 'foo.php', array('role' => 'php'));
$pf->addFile('test', 'foo.txt', array('role' => 'doc'));
$pf->addFile('data', 'foo.dat', array('role' => 'data'));
$pf->addFile('sub/file', 'foo.php', array('role' => 'php'));
$pf->setPackage('foop');
$ret = $reg->addPackage2($pf);
$phpunit->assertTrue($ret, 'install of valid package');
$phpunit->assertNoErrors('install of valid package');
$phpunit->assertFileExists($statedir  . DIRECTORY_SEPARATOR . 'php' .
    DIRECTORY_SEPARATOR . '.registry' . DIRECTORY_SEPARATOR . 'foop.reg', 'reg file of foop.reg');
$contents = unserialize(implode('', file($statedir  . DIRECTORY_SEPARATOR . 'php' .
    DIRECTORY_SEPARATOR . '.registry' . DIRECTORY_SEPARATOR . 'foop.reg')));
$phpunit->showall();
$phpunit->assertTrue(isset($contents['_lastmodified']), '_lastmodified not set pf1');
unset($contents['_lastmodified']);
$phpunit->assertEquals($pf->getArray(), $contents, 'pf1 file saved');

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
$pf2->addFile('', 'foo.php', array('role' => 'php'));
$pf2->addFile('sub/data', 'foo.dat', array('role' => 'data'));
$pf2->addFile('data', 'foo.dat', array('role' => 'data'));
$pf2->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$pf2->setNotes('blah');
$pf2->setPearinstallerDep('1.4.0a1');
$pf2->setPhpDep('4.2.0', '5.0.0');

$cf = new PEAR_ChannelFile;
$cf->setName('grob');
$cf->setServer('grob');
$cf->setSummary('grob');
$cf->setDefaultPEARProtocols();
$reg = &$config->getRegistry();
$reg->addChannel($cf);
$phpunit->assertNoErrors('channel add');

$ret = $reg->checkFileMap($pf->getFilelist(), 'foop', '1.1');
$phpunit->assertEquals(array(), $ret, 'first');

$ret = $reg->checkFileMap($pf2->getFilelist(), array('grob', 'foo'), '1.1');
$phpunit->assertEquals(array('foo.php' => 'foop'), $ret, 'second');

$ret = $reg->deletePackage('foop');
$phpunit->assertTrue($ret, 'delete');

$ret = $reg->addPackage2($pf2);
$phpunit->assertTrue($ret, 'add pf2');

$pf3 = new PEAR_PackageFile_v2;
$pf3->setConfig($config);
$pf3->setPackageType('php');
$pf3->setPackage('foo');
$pf3->setChannel('snork');
$pf3->setAPIStability('stable');
$pf3->setReleaseStability('stable');
$pf3->setAPIVersion('1.0.0');
$pf3->setReleaseVersion('1.0.0');
$pf3->setDate('2004-11-12');
$pf3->setDescription('foo source');
$pf3->setSummary('foo');
$pf3->setLicense('PHP License');
$pf3->setLogger($fakelog);
$pf3->clearContents();
$pf3->addFile('', 'foo.php', array('role' => 'php'));
$pf3->addFile('sub/data', 'foo.dat', array('role' => 'data'));
$pf3->addFile('data', 'foo.dat', array('role' => 'data'));
$pf3->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$pf3->setNotes('blah');
$pf3->setPearinstallerDep('1.4.0a1');
$pf3->setPhpDep('4.2.0', '5.0.0');

$cf = new PEAR_ChannelFile;
$cf->setName('snork');
$cf->setServer('grob');
$cf->setSummary('grob');
$cf->setDefaultPEARProtocols();
$reg = &$config->getRegistry();
$reg->addChannel($cf);
$phpunit->assertNoErrors('channel add');

$ret = $reg->checkFileMap($pf3->getFilelist(), array('snork', 'foo'), '1.1');
$phpunit->assertEquals(array (
  'foo.php' => 
  array (
    0 => 'grob',
    1 => 'foo',
  ),
  'foo' . DIRECTORY_SEPARATOR . 'sub/data/foo.dat' => 
  array (
    0 => 'grob',
    1 => 'foo',
  ),
  'foo' . DIRECTORY_SEPARATOR . 'data/foo.dat' => 
  array (
    0 => 'grob',
    1 => 'foo',
  ),
), $ret, 'second');

echo 'tests done';
?>
--EXPECT--
tests done