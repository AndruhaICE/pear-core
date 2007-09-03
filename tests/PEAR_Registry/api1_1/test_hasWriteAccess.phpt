--TEST--
PEAR_Registry->hasWriteAccess() (API v1.1)
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
--INI--
safe_mode=1
safe_mode_include_dir=/
safe_mode_allowed_env_vars=HOME,PHP_
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
if (OS_UNIX) {
    $phpunit->assertErrorsF(array(
        array('package' => 'PEAR_Error', 'message' => 'registerRoles: opendir(%sPEAR/Installer/Role) failed: does not exist/is not directory')
    ), 'err');
}
if (OS_WINDOWS) {
    $reg->install_dir = '/';
} else {
    $reg->install_dir = '\\';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 1);
if (OS_WINDOWS) {
    $reg->install_dir = '\\';
} else {
    $reg->install_dir = '/';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 2);
if (OS_WINDOWS) {
    $reg->install_dir = '\\$*#';
} else {
    $reg->install_dir = '/$*#';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 3);
if (OS_WINDOWS) {
    $reg->install_dir = '\\windows';
} else {
    $reg->install_dir = '/usr/local/lib/foo/bar';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 4);
?>
===DONE===
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
===DONE===
