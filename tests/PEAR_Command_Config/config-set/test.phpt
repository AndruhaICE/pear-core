--TEST--
config-set command
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
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'php', $config->get('php_dir'), 'setup');
$command->run('config-set', array(), array('php_dir', 'poo'));
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'config-set succeeded',
    'cmd' => 'config-set',
  ),
), $fakelog->getLog(), 'ui log');
$phpunit->assertEquals('poo', $config->get('php_dir'), 'php_dir');

$configinfo = array('master_server' => $server,
    'preferred_state' => 'stable',
    'cache_dir' => $temp_path . DIRECTORY_SEPARATOR . 'cache',
    'php_dir' => 'poo',
    'ext_dir' => $temp_path . DIRECTORY_SEPARATOR . 'ext',
    'data_dir' => $temp_path . DIRECTORY_SEPARATOR . 'data',
    'doc_dir' => $temp_path . DIRECTORY_SEPARATOR . 'doc',
    'test_dir' => $temp_path . DIRECTORY_SEPARATOR . 'test',
    'bin_dir' => $temp_path . DIRECTORY_SEPARATOR . 'bin',
    '__channels' => array('__uri' => array()));

$info = explode("\n", implode('', file($temp_path . DIRECTORY_SEPARATOR . 'pear.ini')));
$info = unserialize($info[1]);
$phpunit->assertEquals($configinfo, $info, 'saved 1');

$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'php', $config->get('php_dir', 'system'), 'setup system');
$command->run('config-set', array(), array('php_dir', 'poo', 'system'));
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'config-set succeeded',
    'cmd' => 'config-set',
  ),
), $fakelog->getLog(), 'ui log');
$phpunit->assertEquals('poo', $config->get('php_dir', 'system'), 'php_dir');
$configinfo = array('master_server' => $server,
    'preferred_state' => 'stable',
    'cache_dir' => $temp_path . DIRECTORY_SEPARATOR . 'cache',
    'php_dir' => 'poo',
    'ext_dir' => $temp_path . DIRECTORY_SEPARATOR . 'ext',
    'data_dir' => $temp_path . DIRECTORY_SEPARATOR . 'data',
    'doc_dir' => $temp_path . DIRECTORY_SEPARATOR . 'doc',
    'test_dir' => $temp_path . DIRECTORY_SEPARATOR . 'test',
    'bin_dir' => $temp_path . DIRECTORY_SEPARATOR . 'bin',
    '__channels' => array('__uri' => array()));

$info = explode("\n", implode('', file($temp_path . DIRECTORY_SEPARATOR . 'pear.conf')));
$info = unserialize($info[1]);
$phpunit->assertEquals($configinfo, $info, 'saved 2');
echo 'tests done';
?>
--EXPECT--
tests done
