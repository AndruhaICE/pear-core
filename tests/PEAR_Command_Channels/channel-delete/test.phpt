--TEST--
channel-delete command
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

$ch = new PEAR_ChannelFile;
$ch->setName('fake');
$ch->setSummary('fake');
$ch->setServer('pear.php.net');
$ch->setDefaultPEARProtocols();
$reg->addChannel($ch);
$chan = $reg->getChannel('fake');
$phpunit->assertIsA('PEAR_ChannelFile', $chan, 'added ok?');
$phpunit->assertEquals('fake', $chan->getName(), 'name ok?');
$e = $command->run('channel-delete', array(), array('fake'));
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 =>
  array (
    'info' => 'Channel "fake" deleted',
    'cmd' => 'no command', 
  ),
), $fakelog->getLog(), 'log');

$chan = $reg->getChannel('fake');
$phpunit->assertFalse($chan, 'after delete');
echo 'tests done';
?>
--EXPECT--
tests done
