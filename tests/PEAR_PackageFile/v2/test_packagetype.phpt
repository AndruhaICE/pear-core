--TEST--
PEAR_PackageFile_Parser_v2 package types
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'Parser'. DIRECTORY_SEPARATOR .
    'test_basicparse'. DIRECTORY_SEPARATOR . 'package2.xml';
$pf = &$parser->parse(implode('', file($pathtopackagexml)), $pathtopackagexml);
$phpunit->assertNoErrors('valid xml parse');
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf, 'return of valid parse');

$phpunit->assertEquals('php', $pf->getPackageType(), 'pre-set');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    'installconditions' => 
    array (
      'os' => 
      array (
        'name' => 'windows',
      ),
    ),
    'filelist' => 
    array (
      'install' => 
      array (
        0 => 
        array (
          'attribs' => 
          array (
            'as' => 'another.php',
            'name' => 'test/test3.php',
          ),
        ),
        1 => 
        array (
          'attribs' => 
          array (
            'as' => 'hi.php',
            'name' => 'test/test2.php',
          ),
        ),
      ),
    ),
  ),
  1 => 
  array (
    'filelist' => 
    array (
      'install' => 
      array (
        'attribs' => 
        array (
          'as' => 'hi.php',
          'name' => 'test/test2.php',
        ),
      ),
      'ignore' => 
      array (
        'attribs' => 
        array (
          'name' => 'test/test3.php',
        ),
      ),
    ),
  ),
), $pf->getReleases(), 'pre-set releases');
$phpunit->assertFalse($pf->setPackageType('foo'), 'foo');
$phpunit->assertEquals(array (
  0 => 
  array (
    'installconditions' => 
    array (
      'os' => 
      array (
        'name' => 'windows',
      ),
    ),
    'filelist' => 
    array (
      'install' => 
      array (
        0 => 
        array (
          'attribs' => 
          array (
            'as' => 'another.php',
            'name' => 'test/test3.php',
          ),
        ),
        1 => 
        array (
          'attribs' => 
          array (
            'as' => 'hi.php',
            'name' => 'test/test2.php',
          ),
        ),
      ),
    ),
  ),
  1 => 
  array (
    'filelist' => 
    array (
      'install' => 
      array (
        'attribs' => 
        array (
          'as' => 'hi.php',
          'name' => 'test/test2.php',
        ),
      ),
      'ignore' => 
      array (
        'attribs' => 
        array (
          'name' => 'test/test3.php',
        ),
      ),
    ),
  ),
), $pf->getReleases(), 'foo releases');
$phpunit->assertTrue($pf->setPackageType('php'), 'php');
$phpunit->assertEquals('php', $pf->getPackageType(), 'php');
$pf->addIgnore('blah.php');
$pf->addRelease();
$phpunit->assertEquals(array (
  0 => 
  array (
    'filelist' => 
    array (
      'ignore' => 
      array (
        'attribs' => 
        array (
          'name' => 'blah.php',
        ),
      ),
    ),
  ),
  1 => 
  array (
  ),
), $pf->getReleases(), 'php releases');

$result = $pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertEquals(array(), $fakelog->getLog(), 'normal validate empty log');
$phpunit->assertNoErrors('after validation');
$result = $pf->validate(PEAR_VALIDATE_INSTALLING);
$phpunit->assertEquals(array(), $fakelog->getLog(), 'installing validate empty log');
$phpunit->assertNoErrors('after validation');
$result = $pf->validate(PEAR_VALIDATE_DOWNLOADING);
$phpunit->assertEquals(array(), $fakelog->getLog(), 'downloading validate empty log');
$phpunit->assertNoErrors('after validation');
$result = $pf->validate(PEAR_VALIDATE_PACKAGING);
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 1,
    1 => 'Analyzing test/test.php',
  ),
  1 => 
  array (
    0 => 1,
    1 => 'Analyzing test/test2.php',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Analyzing test/test3.php',
  ),
), $fakelog->getLog(), 'packaging validate full log');
$phpunit->assertNoErrors('after validation');

$phpunit->assertTrue($pf->setPackageType('extbin'), 'extbin');
$phpunit->assertEquals('extbin', $pf->getPackageType(), 'extbin');
$pf->addIgnore('foo.php');
$pf->addRelease();
$phpunit->assertEquals(array (
  0 => 
  array (
    'filelist' => 
    array (
      'ignore' => 
      array (
        'attribs' => 
        array (
          'name' => 'foo.php',
        ),
      ),
    ),
  ),
  1 => 
  array (
  ),
), $pf->getReleases(), 'extbin releases');
$phpunit->assertTrue($pf->setPackageType('extsrc'), 'extsrc');
$phpunit->assertEquals('extsrc', $pf->getPackageType(), 'extsrc');
$pf->addBinaryPackage('blah');
$pf->addRelease();
$phpunit->assertEquals(array (
  0 => 
  array (
    'binarypackage' => 'blah',
  ),
  1 => 
  array (
  ),
), $pf->getReleases(), 'extsrc releases');
$phpunit->assertTrue($pf->setPackageType('bundle'), 'bundle');
$phpunit->assertEquals(array(), $pf->getReleases(), 'bundle releases');
$phpunit->assertEquals('bundle', $pf->getPackageType(), 'bundle');
$pf->addIgnore('foo.php');
$pf->addRelease();
$phpunit->assertEquals(array (
  0 => 
  array (
    'filelist' => 
    array (
      'ignore' => 
      array (
        'attribs' => 
        array (
          'name' => 'foo.php',
        ),
      ),
    ),
  ),
  1 => 
  array (
  ),
), $pf->getReleases(), 'bundle releases');
echo 'tests done';
?>
--EXPECT--
tests done