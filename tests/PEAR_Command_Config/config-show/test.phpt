--TEST--
config-show command failure
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
$config->set('remote_config', 'blah');
$config->set('preferred_state', 'alpha', 'user', '__uri');
$config->set('preferred_state', 'beta', 'user', 'pear.php.net');
$e = $command->run('config-show', array(), array());
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Configuration (channel pear.php.net):',
      'data' => 
      array (
        'Internet Access' => 
        array (
          0 => 
          array (
            0 => 'Auto-discover new Channels',
            1 => 'auto_discover',
            2 => 0,
          ),
          1 => 
          array (
            0 => 'Default Channel',
            1 => 'default_channel',
            2 => 'pear.php.net',
          ),
          2 => 
          array (
            0 => 'HTTP Proxy Server Address',
            1 => 'http_proxy',
            2 => '',
          ),
          3 => 
          array (
            0 => 'PEAR server [DEPRECATED]',
            1 => 'master_server',
            2 => 'pear.Chiara',
          ),
          4 => 
          array (
            0 => 'Remote Configuration File',
            1 => 'remote_config',
            2 => '********',
          ),
        ),
        'File Locations' => 
        array (
          0 => 
          array (
            0 => 'PEAR executables directory',
            1 => 'bin_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'bin',
          ),
          1 => 
          array (
            0 => 'PEAR documentation directory',
            1 => 'doc_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'doc',
          ),
          2 => 
          array (
            0 => 'PHP extension directory',
            1 => 'ext_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'ext',
          ),
          3 => 
          array (
            0 => 'PEAR directory',
            1 => 'php_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'php',
          ),
        ),
        'File Locations (Advanced)' => 
        array (
          0 => 
          array (
            0 => 'PEAR Installer cache directory',
            1 => 'cache_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'cache',
          ),
          1 => 
          array (
            0 => 'PEAR data directory',
            1 => 'data_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'data',
          ),
          2 => 
          array (
            0 => 'PHP CLI/CGI binary',
            1 => 'php_bin',
            2 => PEAR_CONFIG_DEFAULT_PHP_BIN,
          ),
          3 => 
          array (
            0 => 'PEAR test directory',
            1 => 'test_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'test',
          ),
        ),
        'Advanced' => 
        array (
          0 => 
          array (
            0 => 'Cache TimeToLive',
            1 => 'cache_ttl',
            2 => 3600,
          ),
          1 => 
          array (
            0 => 'Preferred Package State',
            1 => 'preferred_state',
            2 => 'beta',
          ),
          2 => 
          array (
            0 => 'Unix file mask',
            1 => 'umask',
            2 => '0',
          ),
          3 => 
          array (
            0 => 'Debug Log Level',
            1 => 'verbose',
            2 => 1,
          ),
        ),
        'Maintainers' => 
        array (
          0 => 
          array (
            0 => 'PEAR password (for maintainers)',
            1 => 'password',
            2 => '',
          ),
          1 => 
          array (
            0 => 'Signature Handling Program',
            1 => 'sig_bin',
            2 => PEAR_CONFIG_DEFAULT_SIG_BIN,
          ),
          2 => 
          array (
            0 => 'Signature Key Directory',
            1 => 'sig_keydir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'pearkeys',
          ),
          3 => 
          array (
            0 => 'Signature Key Id',
            1 => 'sig_keyid',
            2 => '',
          ),
          4 => 
          array (
            0 => 'Package Signature Type',
            1 => 'sig_type',
            2 => 'gpg',
          ),
          5 => 
          array (
            0 => 'PEAR username (for maintainers)',
            1 => 'username',
            2 => '',
          ),
        ),
        'Config Files' => 
        array (
          0 => 
          array (
            0 => 'User Configuration File',
            1 => 'Filename',
            2 => $temp_path . '/pear.ini',
          ),
          1 => 
          array (
            0 => 'System Configuration File',
            1 => 'Filename',
            2 => $temp_path . '/pear.conf',
          ),
        ),
      ),
    ),
    'cmd' => 'config-show',
  ),
), $fakelog->getLog(), 'normal');

$e = $command->run('config-show', array('channel' => '__uri'), array());
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Configuration (channel __uri):',
      'data' => 
      array (
        'Internet Access' => 
        array (
          0 => 
          array (
            0 => 'Auto-discover new Channels',
            1 => 'auto_discover',
            2 => 0,
          ),
          1 => 
          array (
            0 => 'Default Channel',
            1 => 'default_channel',
            2 => 'pear.php.net',
          ),
          2 => 
          array (
            0 => 'HTTP Proxy Server Address',
            1 => 'http_proxy',
            2 => '',
          ),
          3 => 
          array (
            0 => 'PEAR server [DEPRECATED]',
            1 => 'master_server',
            2 => 'pear.Chiara',
          ),
          4 => 
          array (
            0 => 'Remote Configuration File',
            1 => 'remote_config',
            2 => '********',
          ),
        ),
        'File Locations' => 
        array (
          0 => 
          array (
            0 => 'PEAR executables directory',
            1 => 'bin_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'bin',
          ),
          1 => 
          array (
            0 => 'PEAR documentation directory',
            1 => 'doc_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'doc',
          ),
          2 => 
          array (
            0 => 'PHP extension directory',
            1 => 'ext_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'ext',
          ),
          3 => 
          array (
            0 => 'PEAR directory',
            1 => 'php_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'php',
          ),
        ),
        'File Locations (Advanced)' => 
        array (
          0 => 
          array (
            0 => 'PEAR Installer cache directory',
            1 => 'cache_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'cache',
          ),
          1 => 
          array (
            0 => 'PEAR data directory',
            1 => 'data_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'data',
          ),
          2 => 
          array (
            0 => 'PHP CLI/CGI binary',
            1 => 'php_bin',
            2 => PEAR_CONFIG_DEFAULT_PHP_BIN,
          ),
          3 => 
          array (
            0 => 'PEAR test directory',
            1 => 'test_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'test',
          ),
        ),
        'Advanced' => 
        array (
          0 => 
          array (
            0 => 'Cache TimeToLive',
            1 => 'cache_ttl',
            2 => 3600,
          ),
          1 => 
          array (
            0 => 'Preferred Package State',
            1 => 'preferred_state',
            2 => 'alpha',
          ),
          2 => 
          array (
            0 => 'Unix file mask',
            1 => 'umask',
            2 => '0',
          ),
          3 => 
          array (
            0 => 'Debug Log Level',
            1 => 'verbose',
            2 => 1,
          ),
        ),
        'Maintainers' => 
        array (
          0 => 
          array (
            0 => 'PEAR password (for maintainers)',
            1 => 'password',
            2 => '',
          ),
          1 => 
          array (
            0 => 'Signature Handling Program',
            1 => 'sig_bin',
            2 => PEAR_CONFIG_DEFAULT_SIG_BIN,
          ),
          2 => 
          array (
            0 => 'Signature Key Directory',
            1 => 'sig_keydir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'pearkeys',
          ),
          3 => 
          array (
            0 => 'Signature Key Id',
            1 => 'sig_keyid',
            2 => '',
          ),
          4 => 
          array (
            0 => 'Package Signature Type',
            1 => 'sig_type',
            2 => 'gpg',
          ),
          5 => 
          array (
            0 => 'PEAR username (for maintainers)',
            1 => 'username',
            2 => '',
          ),
        ),
        'Config Files' => 
        array (
          0 => 
          array (
            0 => 'User Configuration File',
            1 => 'Filename',
            2 => $temp_path . '/pear.ini',
          ),
          1 => 
          array (
            0 => 'System Configuration File',
            1 => 'Filename',
            2 => $temp_path . '/pear.conf',
          ),
        ),
      ),
    ),
    'cmd' => 'config-show',
  ),
), $fakelog->getLog(), 'opt');

$config->set('default_channel', '__uri');
$e = $command->run('config-show', array(), array());
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Configuration (channel __uri):',
      'data' => 
      array (
        'Internet Access' => 
        array (
          0 => 
          array (
            0 => 'Auto-discover new Channels',
            1 => 'auto_discover',
            2 => 0,
          ),
          1 => 
          array (
            0 => 'Default Channel',
            1 => 'default_channel',
            2 => '__uri',
          ),
          2 => 
          array (
            0 => 'HTTP Proxy Server Address',
            1 => 'http_proxy',
            2 => '',
          ),
          3 => 
          array (
            0 => 'PEAR server [DEPRECATED]',
            1 => 'master_server',
            2 => 'pear.Chiara',
          ),
          4 => 
          array (
            0 => 'Remote Configuration File',
            1 => 'remote_config',
            2 => '********',
          ),
        ),
        'File Locations' => 
        array (
          0 => 
          array (
            0 => 'PEAR executables directory',
            1 => 'bin_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'bin',
          ),
          1 => 
          array (
            0 => 'PEAR documentation directory',
            1 => 'doc_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'doc',
          ),
          2 => 
          array (
            0 => 'PHP extension directory',
            1 => 'ext_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'ext',
          ),
          3 => 
          array (
            0 => 'PEAR directory',
            1 => 'php_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'php',
          ),
        ),
        'File Locations (Advanced)' => 
        array (
          0 => 
          array (
            0 => 'PEAR Installer cache directory',
            1 => 'cache_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'cache',
          ),
          1 => 
          array (
            0 => 'PEAR data directory',
            1 => 'data_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'data',
          ),
          2 => 
          array (
            0 => 'PHP CLI/CGI binary',
            1 => 'php_bin',
            2 => PEAR_CONFIG_DEFAULT_PHP_BIN,
          ),
          3 => 
          array (
            0 => 'PEAR test directory',
            1 => 'test_dir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'test',
          ),
        ),
        'Advanced' => 
        array (
          0 => 
          array (
            0 => 'Cache TimeToLive',
            1 => 'cache_ttl',
            2 => 3600,
          ),
          1 => 
          array (
            0 => 'Preferred Package State',
            1 => 'preferred_state',
            2 => 'alpha',
          ),
          2 => 
          array (
            0 => 'Unix file mask',
            1 => 'umask',
            2 => '0',
          ),
          3 => 
          array (
            0 => 'Debug Log Level',
            1 => 'verbose',
            2 => 1,
          ),
        ),
        'Maintainers' => 
        array (
          0 => 
          array (
            0 => 'PEAR password (for maintainers)',
            1 => 'password',
            2 => '',
          ),
          1 => 
          array (
            0 => 'Signature Handling Program',
            1 => 'sig_bin',
            2 => PEAR_CONFIG_DEFAULT_SIG_BIN,
          ),
          2 => 
          array (
            0 => 'Signature Key Directory',
            1 => 'sig_keydir',
            2 => $temp_path . DIRECTORY_SEPARATOR . 'pearkeys',
          ),
          3 => 
          array (
            0 => 'Signature Key Id',
            1 => 'sig_keyid',
            2 => '',
          ),
          4 => 
          array (
            0 => 'Package Signature Type',
            1 => 'sig_type',
            2 => 'gpg',
          ),
          5 => 
          array (
            0 => 'PEAR username (for maintainers)',
            1 => 'username',
            2 => '',
          ),
        ),
        'Config Files' => 
        array (
          0 => 
          array (
            0 => 'User Configuration File',
            1 => 'Filename',
            2 => $temp_path . '/pear.ini',
          ),
          1 => 
          array (
            0 => 'System Configuration File',
            1 => 'Filename',
            2 => $temp_path . '/pear.conf',
          ),
        ),
      ),
    ),
    'cmd' => 'config-show',
  ),
), $fakelog->getLog(), 'default channel');
echo 'tests done';
?>
--EXPECT--
tests done