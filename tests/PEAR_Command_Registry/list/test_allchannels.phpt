--TEST--
list command, --allchannels opt
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$e = $command->run('list', array('allchannels' => true), array());
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Installed packages, channel pear.php.net:',
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => '(no packages installed)',
        ),
      ),
    ),
    'cmd' => 'list',
  ),
  1 => 
  array (
    'info' => 
    array (
      'caption' => 'Installed packages, channel __uri:',
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => '(no packages installed)',
        ),
      ),
    ),
    'cmd' => 'list',
  ),
), $fakelog->getLog(), 'no packages installed');

$reg = &$config->getRegistry();
$pkg = &new PEAR_PackageFile($config);
$info = $pkg->fromPackageFile(dirname(__FILE__) . DIRECTORY_SEPARATOR .
    DIRECTORY_SEPARATOR . 'packagefiles' . DIRECTORY_SEPARATOR . 'package2.xml',
    PEAR_VALIDATE_NORMAL);
$reg->addPackage2($info);
require_once 'PEAR/ChannelFile.php';
$ch = new PEAR_ChannelFile;
$ch->setName('gronk');
$ch->setServer('gronk');
$ch->setSummary('gronk');
$reg->addChannel($ch);
$info->setChannel('gronk');
$reg->addPackage2($info);
$info = $pkg->fromPackageFile(dirname(__FILE__) . DIRECTORY_SEPARATOR .
    DIRECTORY_SEPARATOR . 'packagefiles' . DIRECTORY_SEPARATOR . 'package-Console_Getopt.xml',
    PEAR_VALIDATE_NORMAL);
$reg->addPackage2($info);
$e = $command->run('list', array('allchannels' => true), array());
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Installed packages, channel gronk:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Package',
        1 => 'Version',
        2 => 'State',
      ),
      'data' => 
      array (
        0 => 
        array (
          0 => 'PEAR',
          1 => '1.4.0a1',
          2 => 'alpha',
        ),
      ),
    ),
    'cmd' => 'list',
  ),
  1 => 
  array (
    'info' => 
    array (
      'caption' => 'Installed packages, channel pear.php.net:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Package',
        1 => 'Version',
        2 => 'State',
      ),
      'data' => 
      array (
        0 => 
        array (
          0 => 'Console_Getopt',
          1 => '1.2',
          2 => 'stable',
        ),
        1 => 
        array (
          0 => 'PEAR',
          1 => '1.4.0a1',
          2 => 'alpha',
        ),
      ),
    ),
    'cmd' => 'list',
  ),
  2 => 
  array (
    'info' => 
    array (
      'caption' => 'Installed packages, channel __uri:',
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => '(no packages installed)',
        ),
      ),
    ),
    'cmd' => 'list',
  ),
), $fakelog->getLog(), 'installed');
echo 'tests done';
?>
--EXPECT--
tests done
