--TEST--
PEAR_Config->isDefinedLayer()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$config = new PEAR_Config($temp_path . DIRECTORY_SEPARATOR . 'pear.ini');
$phpunit->assertFalse($config->isDefinedLayer('foo'), 'foo');
$phpunit->assertTrue($config->isDefinedLayer('user'), 'user');
$phpunit->assertTrue($config->isDefinedLayer('system'), 'system');
echo 'tests done';
?>
--EXPECT--
tests done
