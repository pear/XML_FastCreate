<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at                              |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Guillaume Lecanu <guillaume.lecanu@online.fr>                |
// +----------------------------------------------------------------------+
//
// $Id$

/**
 * FastCreate package advantages :
 *
 * - Easy way to make valid XML :
 *      $x->div(
 *          $x->h1("Example"),
 *          $x->p("Hello"),
 *          $x->p(array('class'=>'example'), "World !")
 *      )
 *
 * - Option to report DTD errors [ Require XML_DTD ]
 * 
 * - Use the driver output of your choice : Text(string) / XML_Tree(object)
 *   [ Require XML_Tree package for 'XML_Tree' driver ]
 *
 * - Translation option to quickly replace tags by anothers. 
 *   ex: Convert your XML to XHTML : 
 *          <news><title> Example </title></news> 
 *      =>  <div class="news"><h1><span> Example </span></h1></div>
 *
 * - Indentation option of your XML
 *   [ Require XML_Beautifier package ]
 *
 * - File write option to save your XML output.
 *
 * Simple example to make a valid XHTML page :
 * [code]
 * <?php
 *  require_once 'XML/FastCreate.php';
 * 
 *  $x =& XML_FastCreate::factory('Text');
 * 
 *  $x->html(
 *     $x->head(
 *          $x->title("A simple XHTML page")
 *     ),
 *     $x->body(
 *         $x->div(
 *             $x->h1('Example'),
 *             $x->br(),
 *             $x->a(array('href' => 'http://pear.php.net'), 'PEAR WebSite')
 *         )
 *     )
 *  );
 * 
 *  // Write output
 *  $x->toXML();
 * ?>
 * [/code]
 *
 * KNOW BUGS :
 * - XML_DTD is an alpha version
 *      - Some DTD couln't correctly interpreted (like XHTML 1.1)
 *      - You can use an external program like XMLLINT for check validation
 * - See driver used 
 *
 * @package XML_FastCreate
 * @category XML
 */

require_once 'PEAR.php';

// Errors of XML_FastCreate
define('XML_FASTCREATE_ERROR_NO_FACTORY', 1);
define('XML_FASTCREATE_ERROR_NO_DRIVER', 2);
define('XML_FASTCREATE_ERROR_DTD', 3);

// Default filename for 'file' option
define('XML_FASTCREATE_FILE', '/tmp/XML_FastCreate.xml');

// Default program for 'exec' option
define('XML_FASTCREATE_EXEC',
    '/usr/bin/xmllint --valid --noout /tmp/XML_FastCreate.xml 2>&1');

// DocType : XHTML 1.1
define('XML_FASTCREATE_DOCTYPE_XHTML_1_1', 
    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" '
    .'"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">');

// DocType : XHTML 1.0 Strict
define('XML_FASTCREATE_DOCTYPE_XHTML_1_0_STRICT',
    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" '
    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');

// DocType : XHTML 1.0 Transitional
define('XML_FASTCREATE_DOCTYPE_XHTML_1_0_TRANSITIONAL',
    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '
    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');

// DocType : XHTML 1.0 Frameset
define('XML_FASTCREATE_DOCTYPE_XHTML_1_0_FRAMESET',
    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" '
    .'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">');

// DocType : XHTML 4.01 Strict
define('XML_FASTCREATE_DOCTYPE_HTML_4_01_STRICT',
    '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" '
    .'"http://www.w3.org/TR/html4/strict.dtd">');

// DocType : XHTML 4.01 Transitional
define('XML_FASTCREATE_DOCTYPE_HTML_4_01_Transitional',
    '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" '
    .'"http://www.w3.org/TR/html4/loose.dtd">');

// DocType : XHTML 4.01 Frameset
define('XML_FASTCREATE_DOCTYPE_HTML_4_01_Frameset',
    '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" '
    .'"http://www.w3.org/TR/html4/frameset.dtd">');


// Overload class 
if (isSet($_GLOBALS['XML_FASTCREATE_NO_OVERLOAD']) 
    && $_GLOBALS['XML_FASTCREATE_NO_OVERLOAD']) {
    if (is_array($_GLOBALS['XML_FASTCREATE_NO_OVERLOAD'])) {
        $class = 'class XML_FastCreate_Overload extends PEAR {';
        foreach ($_GLOBALS['XML_FASTCREATE_NO_OVERLOAD'] as $tag) {
            $class .= "
            function $tag() { 
                \$args = func_get_args();
                array_unshift(\$args, '$tag');
                return call_user_func_array(array(&\$this, 'xml'), \$args);
            }";
        }
        $class .= ' }';
        eval($class);
    } else {
        class XML_FastCreate_Overload extends PEAR {}
    }
} else {
    if (phpversion() < 5) {
        class XML_FastCreate_Overload extends PEAR {
            function __call($method, $args, &$return)
            {
                if ($method != __CLASS__) {
                    $return = $this->_call($method, $args);
                }
                return true;
            }
        }
        if (function_exists('overload')) {
            overload('XML_FastCreate_Overload');
        }
    } else {
        class XML_FastCreate_Overload extends PEAR {
            function __call($method, $args) 
            {
                if ($method != __CLASS__) {
                    return $this->_call($method, $args);
                }
            }
        }
    }
}

class XML_FastCreate extends XML_FastCreate_Overload {

    /**
    * DTD Filename for check validity of XML
    *
    * @var      string
    * @access   private
    */
    var $_dtd;
    
    /**
    * Enable / disable output indentation
    *
    * @var      boolean
    * @access   private
    */
    var $_indent = false;

    /**
    * Write output to a file
    *
    * @var      string
    * @access   private
    */
    var $_file;
    
    /**
    * Run external command after write output into file
    *
    * @var      string
    * @access   private
    */
    var $_exec;
    
    /*
    * Flag to know if the factory is used
    *
    * @var      boolean
    * @access   private
    */
    var $_factory = false;
    
    /*
    * Name of the driver use
    *
    * @var      string
    * @access   private
    */
    var $_driver;
    
    /*
    * List of tags replacements
    *
    * @var      array
    * @access   private
    */
    var $_translate;
    
    /*
    * String representation of the carriage return
    *
    * @var      string
    * @access   public
    */
    var $cr;
    
    /*
    * String representation of a tabulation
    *
    * @var      string
    * @access   public
    */
    var $tab;
    

    /**
     * Factory 
     * 
     * @param string Driver to use ( Text, XML_Tree .. )
     * @param array List options :
     *
     *  'dtd'       : Set the DTD file to check validity
     *                (this mode required the XML_DTD package)
     *
     *  'indent'   : Enable / disable indentation
     *
     *  (see also driver options)
     * 
     * @return object XML_FastCreate_<driver> 
     *
     * @access public
     */
    function &factory($driver, $options = array())
    {
        @include_once "FastCreate/{$driver}.php";
        $class = 'XML_FastCreate_'.$driver;
        if (!class_exists($class)) {
            return PEAR::raiseError("Unable to include the XML/FastCreate/"
                .$driver.".php file.", XML_FASTCREATE_ERROR_NO_DRIVER, 
                PEAR_ERROR_DIE);
        }
        $obj = new $class($options);
        return $obj;
    }

    /**
     * Constructor method. Use the factory() method to make an instance 
     * 
     * @param array Hashtable containing optional settings :
     *
     *  'output'    : Set the output format : 'XML' text or 'XML_Tree' object
     *
     * @return XML_FastCreate(object)
     * @access public
     */
    function XML_FastCreate($options = array())
    {
        if ($this->_factory) {
        
            $this->PEAR();
            $this->_dtd = (isSet($options['dtd']) 
                        ? $options['dtd'] : '');
            $this->_file = (isSet($options['file']) 
                        ? $options['file'] : '');
            $this->_exec = (isSet($options['exec']) 
                        ? $options['exec'] : '');
            if (isSet($options['indent'])) {
                $this->_indent = $options['indent'];
            }
            if (isSet($options['translate'])) {
                $this->_translate = $options['translate'];
            }
            if ($this->_dtd) {
                include_once 'XML/DTD/XmlValidator.php';
            }
            $this->cr  = chr(13).chr(10);
            $this->tab = chr(9);

        } else {
            PEAR::raiseError("Use the factory() method please.",
                XML_FASTCREATE_ERROR_NO_FACTORY, 
                PEAR_ERROR_DIE);
        }
    }
    

    /**
     * Overloading management
     * 
     * @param string Name of the function overloaded
     * @param array List of arguments of the function overloaded
     * 
     * @return mixed 
     *
     * @access private
     */
    function _call(&$method, &$args) 
    {
        array_unshift($args, $method);
        return call_user_func_array(array(&$this, 'xml'), $args);
    }
    
    /**
     * Print the current XML to standard output
     *
     * @access public
     * @return true or PEAR Error object
     */
    function toXML()
    {
        $xml = $this->getXML();
        if ($this->_indent) {
            $xml = $this->indentXML($xml);
        }
        print $xml;
 
        // Check Validity
        if ($this->_dtd) {
            return $this->isValid($xml);
        }

        // Write output to file
        if ($this->_file) {
            $fp = fopen($this->_file, 'w+');
            fwrite($fp, $xml);
            fclose($fp);
        }
        
        // Run an external program
        if ($this->_exec) {
           $return = shell_exec($this->_exec);
           if ($return) {
                return PEAR::raiseError($return, XML_FASTCREATE_ERROR_DTD);
           }
        }

        return true;
    }

    /**
     * Check if the XML respect the DTD.
     * Require the XML_DTD package
     *
     * @param string The XML text to check
     *
     * @return boolean True if valid
     * @access  public
     */
    function isValid(&$xml)
    {
        $validator = new XML_DTD_XmlValidator;
        $tree = new XML_Tree();
        $nodes = $tree->getTreeFromString($xml);
        if (PEAR::isError($nodes)) {
            return $nodes;
        }
        $parser =& new XML_DTD_Parser;
        $validator->dtd = $parser->parse($this->_dtd);
        $validator->_runTree($nodes);
        if ($validator->_errors) {
            $errors = $validator->getMessage();
            return PEAR::raiseError($errors, XML_FASTCREATE_ERROR_DTD);
        }
        return true;
    }
 
    /**
     * Indent a XML text 
     *
     * @param string $xml Text
     * 
     * @return string The XML text indented
     * @acess public
     */
    function indentXML($xml)
    {
        require_once "XML/Beautifier.php";
        $fmt = new XML_Beautifier();
        $out =& $fmt->formatString($xml);
        return $out;
    }
    
    /** 
     *  Make a XML tag.
     *
     *  Accept all forms of parameters.
     *
     * @param string Name of the tag
     * @param mixed (possible array for attributes)
     * @param mixed (all types for sub tags)
     *
     * @return mixed See the driver specification
     * @access public
     */
    function xml($tag) 
    {
        $attribs = array();
        $args = func_get_args();
        array_shift($args);
        if ((count($args) > 0) && is_array($args[0])) {
            $attribs = $args[0];
            array_shift($args);
        }
        if ($tag{0} == '_') {
            $tag = substr($tag, 1);
        } else {
        
            if (isSet($this->_translate[$tag])) {
                if (isSet($this->_translate[$tag][1])) {
                    $open  =& $this->_translate[$tag][0];
                    $close =& $this->_translate[$tag][1];
                } elseif (isSet($this->_translate[$tag][0])) {
                    $open  = '<'.$this->_translate[$tag][0].'>';
                    $close = '</'.$this->_translate[$tag][0].'>';
                } else {
                    $open  = '<div class="'.$tag.'">';
                    $close = '</div>';
                }
                return $this->importXML($open.$this->exportXML($args).$close);
            } 
        }
        return $this->makeXML($tag, $attribs, $args);
    }
 

    // -------------------------------------------------------- \\
    // --- Abstract methods to be implemented by the driver --- \\
    // -------------------------------------------------------- \\
 
    /**
     * Make a XML Tag 
     *
     * @param string Name of the tag
     * @param array List of attributes
     * @param array List of contents (strings or sub tags)
     * 
     * @return mixed See the driver specifications
     * @access private
     */
    function makeXML($tag, $attribs = array(), $contents = array()) {}

    /**
     * Encode a string to be include in XML tags.
     *
     * To use only if the 'quoteContents' is false
     * Convert :  &      <     >     "       '
     *      To :  &amp;  &lt;  &gt;  &quot;  &apos;
     * 
     * @param string Content to be quoted
     *
     * @return string The quoted content
     * @access  public
     */
    function quote($content) {}

    /**
     * Don't quote this content.
     *
     * To use only if the 'quoteContents' is true
     * 
     * @param string Content to escape quoting
     *
     * @return string The content not quoted
     * @access  public
     */
    function noquote($content) {}

    /**
     * Make a XML comment
     *
     * @param mixed Content to comment
     * 
     * @return mixed See the driver specifications
     * @access public
     */
    function comment($content) {}

    /**
     * Make a CDATA section <![CDATA[ (...) ]]>
     *
     * @param mixed Content of the section
     * 
     * @return mixed See the driver specifications
     * @access public
     */
    function cdata($content) {}

    /**
     * Return the current XML text 
     *
     * @return string The current XML text
     * @access public
     */
    function getXML() {}

    /**
     * Import XML text to driver data
     *
     * @param string The XML text
     * 
     * @return mixed See the driver specifications
     * @access public
     */
    function importXML($xml) {}

    /**
     * Export driver data to XML text
     *
     * @param mixed The XML data driver 
     * 
     * @return string XML text
     * @access public
     */
    function exportXML($xml) {}

}


?>
