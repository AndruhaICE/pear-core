--TEST--
PEAR_Downloader_Package::analyzeDependencies package.xml 2.0
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$mainpackage = dirname(dirname(__FILE__))  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'main-1.0.tgz';
$requiredpackage = dirname(dirname(__FILE__))  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'required-1.1.tgz';
$sub1package = dirname(dirname(__FILE__))  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'sub1-1.1.tgz';
$sub2package = dirname(dirname(__FILE__))  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'sub2-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/main-1.0.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/required-1.1.tgz', $requiredpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/sub1-1.0.tgz', $sub1package);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/sub2-1.0.tgz', $sub2package);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'main', 'channel' => 'pear.php.net', 'group' => 'foo'), 'stable'),
    array('version' => '1.0',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'main',
            'license' => 'PHP License',
            'summary' => 'Main Package',
            'description' => 'Main Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.0',
            'xsdversion' => '2.0',
            'deps' =>
            array(
                'required' =>
                array(
                    'php' =>
                    array(
                        'min' => '4.2',
                        'max' => '6.0.0',
                        ),
                    'pearinstaller' =>
                    array(
                        'min' => '1.4.0dev13',
                        ),
                    'package' =>
                        array(
                            'name' => 'required',
                            'channel' => 'pear.php.net',
                            'min' => '1.1',
                        ),
                ),
                'optional' =>
                array(
                    'package' =>
                        array(
                            'name' => 'optional',
                            'channel' => 'pear.php.net',
                            'min' => '1.1',
                        ),
                ),
                'group' =>
                array(
                    'attribs' => 
                    array(
                        'name' => 'foo',
                        'hint' => 'testing group',
                    ),
                    'package' =>
                        array(
                            array(
                                'name' => 'sub1',
                                'channel' => 'pear.php.net',
                                'min' => '1.1',
                            ),
                            array(
                                'name' => 'sub2',
                                'channel' => 'pear.php.net',
                                'min' => '1.1',
                            ),
                        )
                ),
            ),
          ),
          'url' => 'http://www.example.com/main-1.0'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'required', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    false);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'sub1', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    array('version' => '1.1',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'sub1',
            'license' => 'PHP License',
            'summary' => 'Subgroup Package 1',
            'description' => 'Subgroup Package 1',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.1',
            'xsdversion' => '2.0',
          ),
          'url' => 'http://www.example.com/sub1-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'sub2', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    false);
$dp = &newFakeDownloaderPackage(array());
$result = $dp->initialize('main#foo');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
$phpunit->assertNoErrors('after detect');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'No releases for package "channel://pear.php.net/required" exist, cannot download "dependency"',
  ),
  1 => 
  array (
    0 => 1,
    1 => 'Notice: package "pear.php.net/main" optional dependency "pear.php.net/optional" will not be automatically downloaded, use --alldeps to automatically download optional dependencies',
  ),
  2 => 
  array (
    0 => 0,
    1 => 'No releases for package "channel://pear.php.net/sub2" exist, cannot download "dependency"',
  ),
), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertEquals(1, count($params), 'detectDependencies');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 1');

$err = test_PEAR_Downloader_Package::analyzeDependencies($params);
$phpunit->assertNoErrors('end');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/main-1.0 requires PHP version 4.2 or greater',
  ),
  1 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/main-1.0 requires package "channel://pear.php.net/required" version 1.1 or greater',
  ),
  2 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/main-1.0 can optionally use package "channel://pear.php.net/optional" version 1.1 or greater',
  ),
  3 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/main-1.0 can optionally use package "channel://pear.php.net/sub2" version 1.1 or greater',
  ),
), $fakelog->getLog(), 'end log');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'end download');

echo 'tests done';
?>
--EXPECT--
tests done