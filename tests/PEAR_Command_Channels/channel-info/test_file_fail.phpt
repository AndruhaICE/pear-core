--TEST--
channel-info command (channel.xml file, invalid channel.xml)
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
$c = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<channel version="1.0" xmlns="http://pear.php.net/channel-1.0"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://pear.php.net/dtd/channel-1.0.xsd">
 <name>@#%^*@</name>
 <suggestedalias>froo</suggestedalias>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <servers>
  <primary host="pear.php.net">
   <xmlrpc>
    <function version="1.0">logintest</function>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
    <function version="1.0">package.info</function>
    <function version="1.0">package.getDownloadURL</function>
    <function version="1.0">channel.listAll</function>
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
   </soap>
   <rest>
    <function version="1.0" uri="package/listLatestReleases">package.listLatestReleases</function>
    <function version="1.0" uri="package/listAll">package.listAll</function>
   </rest>
   <static version="1.0"/>
  </primary>
  <mirror host="poor.php.net">
   <xmlrpc>
    <function version="1.0">logintest</function>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
    <function version="1.0">package.info</function>
    <function version="1.0">package.getDownloadURL</function>
    <function version="1.0">channel.listAll</function>
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
   </soap>
   <rest>
    <function version="1.0" uri="package/listLatestReleases">package.listLatestReleases</function>
    <function version="1.0" uri="package/listAll">package.listAll</function>
   </rest>
   <static version="1.0"/>
  </mirror>
 </servers>
</channel>';
$fp = fopen($temp_path . DIRECTORY_SEPARATOR . 'channel.xml', 'wb');
fwrite($fp, $c);
fclose($fp);
$e = $command->run('channel-info', array(), array($temp_path . DIRECTORY_SEPARATOR . 'channel.xml'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'Channel file "' . $temp_path .
        DIRECTORY_SEPARATOR . 'channel.xml" is not valid'),
    array('package' => 'PEAR_ChannelFile', 'message' => 'Invalid channel name "@#%^*@"'),
), '1');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'error: Invalid channel name "@#%^*@"',
    'cmd' => 'no command',
  ),
), $fakelog->getLog(), 'log 1');
echo 'tests done';
?>
--EXPECT--
tests done