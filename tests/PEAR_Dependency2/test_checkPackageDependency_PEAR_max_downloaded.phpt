--TEST--
PEAR_Dependency2->checkPackageDependency() max (downloaded) failure
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$dep = &new test_PEAR_Dependency2($config, array(), array('channel' => 'pear.php.net',
    'package' => 'mine'), PEAR_VALIDATE_DOWNLOADING);
$phpunit->assertNoErrors('create 1');

require_once 'PEAR/Downloader/Package.php';
require_once 'PEAR/Downloader.php';
$down = new PEAR_Downloader($fakelog, array(), $config);
$dp = &new PEAR_Downloader_Package($down);
$dp->initialize(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'package.xml');
$params = array(&$dp);

$result = $dep->validatePackageDependency(
    array(
        'name' => 'foo',
        'channel' => 'pear.php.net',
        'min' => '0.1',
        'max' => '0.9',
    ), true, $params);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error',
          'message' => 'channel://pear.php.net/mine requires package "channel://pear.php.net/foo" (version >= 0.1, version <= 0.9), downloaded version is 1.0')
), 'min');
$phpunit->assertIsa('PEAR_Error', $result, 'min');

// optional
$result = $dep->validatePackageDependency(
    array(
        'name' => 'foo',
        'channel' => 'pear.php.net',
        'min' => '0.1',
        'max' => '0.9',
    ), false, $params);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error',
          'message' => 'channel://pear.php.net/mine requires package "channel://pear.php.net/foo" (version >= 0.1, version <= 0.9), downloaded version is 1.0')
), 'min optional');
$phpunit->assertIsa('PEAR_Error', $result, 'min optional');

echo 'tests done';
?>
--EXPECT--
tests done