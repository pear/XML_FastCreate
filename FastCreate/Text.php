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
 * Text driver for XML_FastCreate.
 * Use standard string to make XML.
 *
 *  $x =& XML_FastCreate::factory('Text',
 *
 *            // Options list of this driver
 *            array(
 *                
 *              // Use the XHTML 1.0 Strict doctype
 *              'doctype'   :   XML_FASTCREATE_DOCTYPE_XHTML_1_0_STRICT
 *            ),
 *
 *            // Options list of FastCreate
 *            array(
 *
 *            )
 *     );
 *  
 */

require_once 'XML/FastCreate.php';

class XML_FastCreate_Text extends XML_FastCreate
{
 var $_factory = true;
 var $_options = array();
 var $xml = '';

    /** 
     *  Constructor
     *
     *  @param array List of options 
     *
     *      'version' :     Set the XML version (default = '1.0')
     *
     *      'encoding' :    Set the encoding charset (default = 'UTF-8')
     *
     *      'standalone' :  Set the standalone attribute (default = 'no')
     *
     *      'doctype'   :   DocType string, set manually or use :
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_1
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_0_STRICT
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_0_FRAMESET
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_0_TRANSITIONAL
     *          XML_FASTCREATE_DOCTYPE_HTML_4_01_STRICT
     *          XML_FASTCREATE_DOCTYPE_HTML_4_01_FRAMESET
     *          XML_FASTCREATE_DOCTYPE_HTML_4_01_TRANSITIONAL
     *
     *      'quote' : auto quote attributes & contents (default = true)
     *
     *   Specials options :
     *
     *      'expand' :  Return single tag with the syntax : 
     *                  <tag></tag> rather <tag /> (default = false)
     *                  ( set to true if you write HTML )
     *          
     *      'apos' :    Quote apostrophe to its entitie &apos; (default = true) 
     *                  <! WARNING !>
     *                  For valid XML, you must let this option to true.
     *                  If you write XHTML, Internet Explorer won't recognize 
     *                  this entitie, so turn this option to false.
     *
     *      'singleAttribute' : Accept single attributes (default = false)
     *            ex :  $x->input(array('type'=>'checkbox', checked=>true))
     *              =>  <input type="checkbox" checked />
     *                  <! WARNING !> 
     *                  This syntax is not valid XML.
     *                  For valid XML, don't use this option.
     *            ex :  $x->input(array('type'=>'checkbox', checked=>'checked'))
     *              =>  <input type="checkbox" checked=>"checked" />
     *
     *  @return object XML_FastCreate_Text
     *  @access public
     */
    function XML_FastCreate_Text($options = array())
    {
        $this->XML_FastCreate($options);
        $this->_driver = 'Text';
        $this->_options = $options;
        if (!isSet($options['version'])) {
            $this->_options['version'] = '1.0';
        }
        if (!isSet($options['encoding'])) {
            $this->_options['encoding'] = 'UTF-8';
        }
        if (!isSet($options['standalone'])) {
            $this->_options['standalone'] = 'no';
        }
        if (!isSet($options['doctype'])) {
            $this->_options['doctype'] = '';
        }
        if (!isSet($options['quote'])) {
            $this->_options['quote'] = true;
        }
        if (!isSet($options['expand'])) {
            $this->_options['expand'] = false;
        }
        if (!isSet($options['singleAttribute'])) {
            $this->_options['singleAttribute'] = false;
        }
    }

    /**
     * Make a XML tag 
     * 
     * @param string Name of the tag
     * @param array List of attributes
     * @param array List of contents (strings or sub tags)
     *
     * @return string reprensenting the XML tag
     * @access public
     */
    function makeXML($tag, $attribs = array(), $contents = array())
    {
        $attTxt = '';
        foreach ($attribs as $attrib => $value) {
            if (is_bool($value) && $value 
            && $this->_options['singleAttribute']) {
                if ($this->_options['quote']) {
                    $attrib = $this->_quoteEntities($attrib);
                }
                $attTxt .= " $attrib";
            } else {
                if ($this->_options['quote']) {
                    $attrib = $this->_quoteEntities($attrib);
                    $value = $this->_quoteEntities($value);
                }
                $attTxt .= ' '.$attrib.'="'.$value.'"';
            }
        }
        if (count($contents) > 0) {
            $element = '<'.$tag.$attTxt.'>';
            foreach ($contents as $content) {
                if ($this->_options['quote']) {
                    $content = $this->quote($content);
                }
                $element .= $content;
            }
            $element .= "</$tag>";
        } else {
            if ($this->_options['expand']) {
                $element = "<$tag$attTxt></$tag>";
            } else {
                $element = "<$tag$attTxt />";
            }
        }
        $this->xml = $this->cr.$this->_quoted($element);
        return $this->xml;
    }

    
    /**
     * Make a XML comment
     *
     * @param string The content to comment
     * 
     * @return string The content commented
     * @access public
     */
    function comment($content)
    {
        return $this->_quoted('<!-- '.$content.' -->');
    }

    
    /**
     * Make a CDATA section <![CDATA[ (...) ]]> 
     *
     * @param string The content of the section
     * 
     * @return string The CDATA section
     * @access public
     */
    function cdata($content)
    {
        return  $this->_quoted('/*<![CDATA[*/'
                .$this->cr.$content
                .$this->cr.'/*]]>*/');
    }


    /**
     * Return the current XML text.
     *
     * @return string The current XML text
     * @access public
     */
    function getXML()
    {
        $header = '<?xml'
                .' version="'.$this->_options['version'].'"'
                .' encoding="'.$this->_options['encoding'].'"'
                .' standalone="'.$this->_options['standalone'].'" ?>';
        if ($this->_options['doctype']) {
            $header .= "\n".$this->_options['doctype'];
        }
        return $header.$this->_unquote($this->xml);
    }

    /**
     * Import XML text to driver data
     *
     * @param string The XML text
     * 
     * @return string The XML text
     * @access public
     */
    function importXML($xml) 
    {
        return $this->_quoted($xml);
    }
    
    /**
     * Export driver data to XML text 
     *
     * @param array List of contents
     * 
     * @return string The XML text
     * @access public
     */
    function exportXML($data = array()) 
    {
        $xml = '';
        foreach ($data as $str) {
            $xml .= $str;
        }
        return $this->cr.$xml.$this->cr;
    }

    /**
     * Encode a string to be include in XML tags.
     *
     * To use only if the 'quote' is false
     * Convert :  &      <     >     "       '
     *      To :  &amp;  &lt;  &gt;  &quot;  &apos;
     * 
     * @param string Content to be quoted
     *
     * @return string The quoted content
     * @access  public
     */
    function quote($str)
    {
        if (is_string($str)) {
            $len = strlen($str);
            $new = $toQuote = '';
            $waitEnd  = false;
            for ($i=0; $i < $len; $i++) {
                if ($str{$i} == '<') {
                    if (($str{$i+1} == '_') && ($str{$i+2} == '>')) {
                        $new .= $this->_quoteEntities($toQuote);
                        $toQuote = '';
                        $waitEnd = true;
                        $i += 3;
                    }
                }
                if ($waitEnd && ($str{$i} == '<')) {
                    if (($str{$i+1} == '/') && ($str{$i+2} == '_') 
                        && ($str{$i+3} == '>')) {
                        $waitEnd = false;
                        $i += 4;
                        if ($i > $len) { 
                            $i--;
                        }
                    }
                }
                if ($waitEnd) {
                    $new .= $str{$i};
                } else {
                    if ($i < $len) {
                        $toQuote .= $str{$i};
                    }
                }
            }
            $new = '<_>'.$new.$this->_quoteEntities($toQuote).'</_>';
        }
        return $new;
    }

    /**
     *  Don't quote 
     *
     *  @param string Content
     *  @return string Content without "quoted tags"
     *  @access private 
     */
    function noquote($str) 
    {
        return '<_>'.$str.'</_>';
    }

    /**
     *  Remove all "quoted tags"
     *
     *  @param string Content
     *  @return string Content without "quoted tags"
     *  @access private 
     */
    function _unquote($str) 
    {
        return str_replace(array('<_>', '</_>'), array('', ''), $str);
    }


    /** 
     *  Define this content 'quoted'
     *
     *  @param string Content to declare quoted
     *  @return string Content quoted
     *  @access private
     */
    function _quoted($content) 
    {
        if ($this->_options['quote']) {
            return '<_>'.$this->_unquote($content).'</_>';
        }
        return $content;
    }

}

?>
