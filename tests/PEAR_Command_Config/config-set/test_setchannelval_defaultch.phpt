--TEST--
config-set command, channel-specific value
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
$config->set('default_channel', '__uri');
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'php', $config->get('php_dir', 'user', '__uri'), 'setup default_ch');
$command->run('config-set', array(), array('php_dir', 'poo', 'user'));
$phpunit->assertNoErrors('after user default_ch');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'config-set succeeded',
    'cmd' => 'config-set',
  ),
), $fakelog->getLog(), 'ui log user default_ch');
$phpunit->assertEquals('poo', $config->get('php_dir', 'user', '__uri'), 'php_dir user default_ch');
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'php', $config->get('php_dir', 'system', '__uri'), 'setup system default_ch');
$command->run('config-set', array(), array('php_dir', 'poo', 'system'));
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'config-set succeeded',
    'cmd' => 'config-set',
  ),
), $fakelog->getLog(), 'ui log system default_ch');
$phpunit->assertEquals('poo', $config->get('php_dir', 'system', '__uri'), 'php_dir system default_ch');
$configinfo = array('master_server' => $server,
    'preferred_state' => 'stable',
    'cache_dir' => $temp_path . DIRECTORY_SEPARATOR . 'cache',
    'php_dir' => $temp_path . DIRECTORY_SEPARATOR . 'php',
    'ext_dir' => $temp_path . DIRECTORY_SEPARATOR . 'ext',
    'data_dir' => $temp_path . DIRECTORY_SEPARATOR . 'data',
    'doc_dir' => $temp_path . DIRECTORY_SEPARATOR . 'doc',
    'test_dir' => $temp_path . DIRECTORY_SEPARATOR . 'test',
    'bin_dir' => $temp_path . DIRECTORY_SEPARATOR . 'bin',
    '__channels' => array('__uri' => array('php_dir' => 'poo')),
    'default_channel' => '__uri');

$info = explode("\n", implode('', file($temp_path . DIRECTORY_SEPARATOR . 'pear.ini')));
$info = unserialize($info[1]);
$phpunit->assertEquals($configinfo, $info, 'saved 1');

unset($configinfo['default_channel']);
$info = explode("\n", implode('', file($temp_path . DIRECTORY_SEPARATOR . 'pear.conf')));
$info = unserialize($info[1]);
$phpunit->assertEquals($configinfo, $info, 'saved 2');
echo 'tests done';
?>
--EXPECT--
tests done