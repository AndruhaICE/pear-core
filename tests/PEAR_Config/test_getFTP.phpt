--TEST--
PEAR_Config->getFTP()
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
ini_set('include_path', '####');
$config = new PEAR_Config('', '', 'ftp://example.com/config.ini');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error','message' => 'Net_FTP must be installed to use remote config'),
), 'no net_ftp');

$phpunit->assertFalse($config->getFTP(), 'getFTP() false');
include_once dirname(__FILE__) . '/test_readFTPConfigFile/FTP.php.inc';
$ftp = &Net_FTP::singleton();
$ftp->addRemoteFile('config.ini', dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'test_readFTPConfigFile' . DIRECTORY_SEPARATOR . 'remote.ini');
$config = &new PEAR_Config('', '', 'ftp://example.com/config.ini');
$phpunit->assertNoErrors('good ftp');
$phpunit->assertIsa('Net_FTP', $config->getFTP(), 'good ftp');
echo 'tests done';
?>
--EXPECT--
tests done
