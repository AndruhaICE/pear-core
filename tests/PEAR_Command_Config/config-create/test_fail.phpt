--TEST--
config-create command failure
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
$e = $command->run('config-create', array(), array());
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'config-create: must have 2 parameters, root path and filename to save as'),
), 'no params');
$e = $command->run('config-create', array(), array('hoo'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'config-create: must have 2 parameters, root path and filename to save as'),
), '1 params');
$e = $command->run('config-create', array(), array('default_channel', 'user', 'hoo'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'config-create: must have 2 parameters, root path and filename to save as'),
), '3 params');
$e = $command->run('config-create', array(), array('badroot', $temp_path . '/config.ini'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'Root directory must be an absolute path beginning with "/", was: "badroot"'),
), 'bad root dir');
$phpunit->assertFileNotExists($temp_path . '/config.ini', 'make sure no create');
$e = $command->run('config-create', array(), array('/okroot', $temp_path . '/#\\##/'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'Could not create "' . $temp_path . '/#\##/"'),
), 'bad file');
$phpunit->assertFileNotExists($temp_path . '/#\\##/', 'make sure no create #\\##/');
echo 'tests done';
?>
--EXPECT--
tests done
