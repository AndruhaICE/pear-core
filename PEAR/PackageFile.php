<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Gregory Beaver <cellog@php.net>                             |
// +----------------------------------------------------------------------+
//
// $Id$
require_once 'PEAR/Common.php';
require_once 'PEAR/Validate.php';
/**
 * Abstraction for the package.xml package description file
 *
 * @author Gregory Beaver <cellog@php.net>
 * @version $Revision$
 * @stability devel
 * @package PEAR
 */
class PEAR_PackageFile
{
    var $_config;
    var $_debug;
    var $_tmpdir;
    function PEAR_PackageFile(&$config, $debug = false, $tmpdir = false)
    {
        $this->_config = $config;
        $this->_debug = $debug;
        $this->_tmpdir = $tmpdir;
    }

    function &parserFactory($version, $phpversion = false)
    {
        if (!in_array($version{0}, array('1', '2'))) {
            $a = false;
            return $a;
        }
        if ($phpversion) {
            if (!in_array((int) $phpversion, array(4, 5))) {
                $phpversion = 'PHP4';
            }
        } else {
            $v = phpversion();
            if (!in_array((int) $v{0}, array(4, 5))) {
                $v = 5;
            }
            $phpversion = 'PHP' . $v{0};
        }
        include_once 'PEAR/PackageFile/Parser/v' . $version{0} . '.php';
        $version = $version{0};
        $class = "PEAR_PackageFile_Parser_v$version";
        $a = new $class;
        return $a;
    }

    function &factory($version, $phpversion = false)
    {
        if (!in_array($version{0}, array('1', '2'))) {
            $a = false;
            return $a;
        }
        if ($phpversion) {
            if (!in_array((int) $phpversion, array(4, 5))) {
                $phpversion = 'PHP4';
            }
        } else {
            $v = phpversion();
            if (!in_array((int) $v{0}, array(4, 5))) {
                $v = 5;
            }
            $phpversion = 'PHP' . $v{0};
        }
        include_once 'PEAR/PackageFile/v' . $version{0} . '.php';
        $version = $version{0};
        $class = "PEAR_PackageFile_v$version";
        $a = new $class;
        return $a;
    }

    /**
     * Return a packagefile object from its toArray() method
     *
     * WARNING: no validation is performed, the array is assumed to be valid,
     * always parse from xml if you want validation.
     * @param array
     * @return PEAR_PackageFileManager_v1|PEAR_PackageFileManager_v2
     */
    function &fromArray($arr)
    {
        if (isset($arr['xsdversion'])) {
            $obj = &PEAR_PackageFile::factory($arr['xsdversion']);
            $obj->fromArray($arr);
            return $obj;
        } else {
            if (isset($arr['package']['attribs']['version'])) {
                $obj = &PEAR_PackageFile::factory($arr['package']['attribs']['version']);
            } else {
                $obj = &PEAR_PackageFile::factory('1.0');
            }
            $obj->fromArray($arr);
            return $obj;
        }
    }

    /**
     * @param string contents of package.xml file
     * @param int package state (one of PEAR_VALIDATE_* constants)
     * @param string full path to the package.xml file (and the files it references)
     * @return PEAR_PackageFileManager_v1|PEAR_PackageFileManager_v2
     */
    function &fromXmlString($data, $state, $file, $archive = false)
    {
        if (preg_match('/<package[^>]+version="([0-9]+\.[0-9]+)"/', $data, $packageversion)) {
            if (!in_array($packageversion[1], array('1.0', '2.0'))) {
                return PEAR::raiseError('package.xml version "' . $packageversion[1] .
                    '" is not supported, only 1.0 and 2.0 are supported.');
            }
            $object = &PEAR_PackageFile::parserFactory($packageversion[1]);
            $object->setConfig($this->_config);
            $pf = $object->parse($data, $file, $archive);
            if (PEAR::isError($pf)) {
                return $pf;
            }
            if ($pf->validate($state)) {
                return $pf;
            } else {
                $a = PEAR::raiseError('Parsing of package.xml from file "' . $file . '" failed',
                    2, null, null, $pf->getValidationWarnings());
                return $a;
            }
        } else {
//            PEAR_Error_Stack::staticPush('PEAR_PackageFile', 
//                PEAR_PACKAGEFILE_ERROR_NO_PACKAGEVERSION,
//                'warning', array('xml' => $data));
            $object = &PEAR_PackageFile::parserFactory('1.0');
            $object->setConfig($this->_config);
            $pf = $object->parse($data, $state, $file, $archive);
            if (PEAR::isError($pf)) {
                return $pf;
            }
            if ($pf->validate($state)) {
                return $pf;
            } else {
                $a = PEAR::raiseError('Parsing of package.xml from file "' . $file . '" failed',
                    2, null, null, $pf->getValidationWarnings());
                return $a;
            }
        }
    }
    
    // {{{ addTempFile()

    /**
     * Register a temporary file or directory.  When the destructor is
     * executed, all registered temporary files and directories are
     * removed.
     *
     * @param string  $file  name of file or directory
     */
    function addTempFile($file)
    {
        $GLOBALS['_PEAR_Common_tempfiles'][] = $file;
    }
    
    /**
     * @param string contents of package.xml file
     * @param int package state (one of PEAR_VALIDATE_* constants)
     * @return bool success of parsing
     */
    function &fromTgzFile($file, $state)
    {
        $tar = new Archive_Tar($file);
        if ($this->_debug <= 1) {
            $tar->pushErrorHandling(PEAR_ERROR_RETURN);
        }
        $content = $tar->listContent();
        if ($this->_debug <= 1) {
            $tar->popErrorHandling();
        }
        if (!is_array($content)) {
            if (!@is_file($file)) {
                $ret = PEAR::raiseError("could not open file \"$file\"");
                return $ret;
            }
            $file = realpath($file);
            $ret = PEAR::raiseError("Could not get contents of package \"$file\"".
                                     '. Invalid tgz file.');
            return $ret;                                    
        } else {
            if (!count($content) && !@is_file($file)) {
                $ret = PEAR::raiseError("could not open file \"$file\"");
                return $ret;
            }
        }
        $xml = null;
        $origfile = $file;
        foreach ($content as $file) {
            $name = $file['filename'];
            if ($name == 'package.xml') {
                $xml = $name;
                break;
            } elseif (ereg('package.xml$', $name, $match)) {
                $xml = $match[0];
                break;
            }
        }
        if ($this->_tmpdir) {
            $tmpdir = $this->_tmpdir;
        } else {
            $tmpdir = System::mkTemp(array('-d', 'pear'));
            PEAR_PackageFile::addTempFile($tmpdir);
        }
        if (!$xml || !$tar->extractList(array($xml), $tmpdir)) {
            $ret = PEAR::raiseError('could not extract the package.xml file from "' .
                $origfile . '"');
            return $ret;
        }
        $ret = &PEAR_PackageFile::fromPackageFile("$tmpdir/$xml", $state, $origfile);
        return $ret;
    }
    
    /**
     * Returns information about a package file.  Expects the name of
     * a package xml file as input.
     *
     * @param string  $descfile  name of package xml file
     * @return array  array with package information
     * @access public
     * @static
     */
    function &fromPackageFile($descfile, $state, $archive = false)
    {
        if (!@is_file($descfile) || !is_readable($descfile) ||
             (!$fp = @fopen($descfile, 'r'))) {
            return PEAR::raiseError("Unable to open $descfile");
        }

        // read the whole thing so we only get one cdata callback
        // for each block of cdata
        $data = fread($fp, filesize($descfile));
        $ret = &PEAR_PackageFile::fromXmlString($data, $state, $descfile, $archive);
        return $ret;
    }


    /**
     * Returns package information from different sources
     *
     * This method is able to extract information about a package
     * from a .tgz archive or from a XML package definition file.
     *
     * @access public
     * @return string
     * @static
     */
    function &fromAnyFile($info, $state)
    {
        $fp = false;
        if (is_string($info) && (file_exists($info) || ($fp = @fopen($info, 'r')))) {
            if ($fp) {
                fclose($fp);
            }
            $tmp = substr($info, -4);
            if ($tmp == '.xml') {
                $info = &PEAR_PackageFile::fromPackageFile($info, $state);
            } elseif ($tmp == '.tar' || $tmp == '.tgz') {
                $info = &PEAR_PackageFile::fromTgzFile($info, $state);
            } else {
                $fp = fopen($info, "r");
                $test = fread($fp, 5);
                fclose($fp);
                if ($test == "<?xml") {
                    $info = &PEAR_PackageFile::fromPackageFile($info, $state);
                } else {
                    $info = &PEAR_PackageFile::fromTgzFile($info, $state);
                }
            }
        } else {
            return PEAR::raiseError("Cannot open '$info' for parsing");
        }
        return $info;
    }

}