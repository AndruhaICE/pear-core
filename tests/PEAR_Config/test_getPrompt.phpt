--TEST--
PEAR_Config->getPrompt()
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
$config = new PEAR_Config;
$phpunit->assertEquals('Default Channel', $config->getPrompt('default_channel'), 'default_channel');
$phpunit->assertEquals('Remote Configuration File', $config->getPrompt('remote_config'), 'remote_config');
$phpunit->assertEquals('Auto-discover new Channels', $config->getPrompt('auto_discover'), 'auto_discover');
$phpunit->assertEquals('PEAR server [DEPRECATED]', $config->getPrompt('master_server'), 'master_server');
$phpunit->assertEquals('HTTP Proxy Server Address', $config->getPrompt('http_proxy'), 'http_proxy');

$phpunit->assertEquals('PEAR directory', $config->getPrompt('php_dir'), 'php_dir');
$phpunit->assertEquals('PEAR data directory', $config->getPrompt('data_dir'), 'data_dir');
$phpunit->assertEquals('PEAR documentation directory', $config->getPrompt('doc_dir'), 'doc_dir');
$phpunit->assertEquals('PHP extension directory', $config->getPrompt('ext_dir'), 'ext_dir');
$phpunit->assertEquals('PEAR test directory', $config->getPrompt('test_dir'), 'test_dir');
$phpunit->assertEquals('PEAR executables directory', $config->getPrompt('bin_dir'), 'bin_dir');
$phpunit->assertEquals('PEAR Installer cache directory', $config->getPrompt('cache_dir'), 'cache_dir');
$phpunit->assertEquals('PHP CLI/CGI binary', $config->getPrompt('php_bin'), 'php_bin');

$phpunit->assertEquals('PEAR username (for maintainers)', $config->getPrompt('username'), 'username');
$phpunit->assertEquals('PEAR password (for maintainers)', $config->getPrompt('password'), 'password');

$phpunit->assertEquals('Debug Log Level', $config->getPrompt('verbose'), 'verbose');
$phpunit->assertEquals('Preferred Package State', $config->getPrompt('preferred_state'), 'preferred_state');
$phpunit->assertEquals('Unix file mask', $config->getPrompt('umask'), 'umask');
$phpunit->assertEquals('Cache TimeToLive', $config->getPrompt('cache_ttl'), 'cache_ttl');
$phpunit->assertEquals('Package Signature Type', $config->getPrompt('sig_type'), 'sig_type');
$phpunit->assertEquals('Signature Handling Program', $config->getPrompt('sig_bin'), 'sig_bin');
$phpunit->assertEquals('Signature Key Id', $config->getPrompt('sig_keyid'), 'sig_keyid');
$phpunit->assertEquals('Signature Key Directory', $config->getPrompt('sig_keydir'), 'sig_keydir');
echo 'tests done';
?>
--EXPECT--
tests done
