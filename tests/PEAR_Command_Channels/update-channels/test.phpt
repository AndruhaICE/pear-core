--TEST--
update-channels command
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
$reg = &$config->getRegistry();
$c = &$reg->getChannel(strtolower('pear.php.net'));
$lastmod = $c->lastModified();
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'channel.listAll',
    null,
    array(
        'pear.php.net',
        'zornk.ornk.org',
        'horde.orde.de',
    ));
$pathtochannelxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'files'. DIRECTORY_SEPARATOR . 'pearchannel.xml';
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/channel.xml', $pathtochannelxml);
$pathtochannelxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'files'. DIRECTORY_SEPARATOR . 'zornkchannel.xml';
$GLOBALS['pearweb']->addHtmlConfig('http://zornk.ornk.org/channel.xml', $pathtochannelxml);
$pathtochannelxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'files'. DIRECTORY_SEPARATOR . 'hordechannel.xml';
$GLOBALS['pearweb']->addHtmlConfig('http://horde.orde.de/channel.xml', $pathtochannelxml);

$e = $command->run('update-channels', array(), array());
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 =>
  array (
    'info' => 'Updating channel "pear.php.net"',
    'cmd' => 'no command',
  ),
  1 =>
  array (
    'info' => 'Adding new channel "zornk.ornk.org"',
    'cmd' => 'no command', 
  ),
  2 =>
  array (
    'info' => 'Adding new channel "horde.orde.de"',
    'cmd' => 'no command', 
  ),
), $fakelog->getLog(), 'log');

$reg = &new PEAR_Registry($temp_path . DIRECTORY_SEPARATOR . 'php');
$chan = $reg->getChannel('pear.php.net');
$phpunit->assertIsA('PEAR_ChannelFile', $chan, 'updated ok?');
$phpunit->assertEquals('pear.php.net', $chan->getName(), 'name ok?');
$phpunit->assertEquals('foo', $chan->getSummary(), 'summary ok?');
echo 'tests done';
?>
--EXPECT--
tests done
