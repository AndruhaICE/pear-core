--TEST--
PEAR_Downloader_Package->detectDependencies(), optional dep package.xml 1.0 --alldeps
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$mainpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'mainold-1.1.tgz';
$requiredpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'required-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/mainold-1.1.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/required-1.1.tgz', $requiredpackage);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'mainold', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.1',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'mainold',
            'license' => 'PHP License',
            'summary' => 'Main Package',
            'description' => 'Main Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'xsdversion' => '1.0',
            'deps' =>
            array(
                array(
                    'type' => 'pkg',
                    'rel' => 'ge',
                    'name' => 'required',
                    'version' => '1.1',
                    'optional' => 'yes',
                )
            ),
          ),
          'url' => 'http://www.example.com/mainold-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0', array('type' => 'pkg', 'rel' => 'ge', 'name' => 'required', 'version' => '1.1',
        'optional' => 'yes', 'channel' => 'pear.php.net', 'package' => 'required'),
        array('channel' => 'pear.php.net', 'package' => 'mainold', 'version' => '1.1'), 'stable'),
    array('version' => '1.1',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'required',
            'license' => 'PHP License',
            'summary' => 'Required Package',
            'description' => 'Required Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.0',
            'xsdversion' => '2.0',
          ),
          'url' => 'http://www.example.com/required-1.1'));
$dp = &newDownloaderPackage(array('alldeps' => true));
$result = $dp->initialize('mainold');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
$phpunit->assertNoErrors('after detect');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertEquals(1, count($params), 'detectDependencies');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 1');
$phpunit->assertTrue($result, 'first return');
$phpunit->assertEquals(2, count($params), 'mergeDependencies');
$phpunit->assertEquals('mainold', $params[0]->getPackage(), 'main package');
$phpunit->assertEquals('required', $params[1]->getPackage(), 'main package');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 2');
$phpunit->assertFalse($result, 'second return');
echo 'tests done';
?>
--EXPECT--
tests done