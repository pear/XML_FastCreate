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
 * XML_Tree driver for XML_FastCreate.
 * Use XML_Tree object rather text string
 *
 *  $x =& XML_FastCreate::factory('XML_Tree',
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
 * KNOW BUGS :
 * - XML_Tree is a beta version, some features don't work in XML_Tree output
 *      - noquote(), comment() and cdata() are not yet possible with XML_Tree
 *      - singleAttribute option is not possible 
 *      - expand option cannot be disable
 */

require_once 'XML/FastCreate.php';
require_once 'XML/Tree.php';

class XML_FastCreate_XML_Tree extends XML_FastCreate
{
 var $_factory = true;
 var $_options = array();
 var $xml;

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
     *      'doctype'   : DocType string, set manually or use :
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_1
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_0_STRICT
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_0_FRAMESET
     *          XML_FASTCREATE_DOCTYPE_XHTML_1_0_TRANSITIONAL
     *          XML_FASTCREATE_DOCTYPE_HTML_4_01_STRICT
     *          XML_FASTCREATE_DOCTYPE_HTML_4_01_FRAMESET
     *          XML_FASTCREATE_DOCTYPE_HTML_4_01_TRANSITIONAL
     *
     *      'quote' : auto-quote attributes & contents (default = true)
     *
     *  @return object XML_Tree
     *  @access public
     */
    function XML_FastCreate_XML_Tree($options = array())
    {
        $this->XML_FastCreate($options);
        $this->_driver = 'XML_Tree';
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
    }

    /**
     * Make a XML tag 
     * 
     * @param string Name of the tag
     * @param array List of attributes
     * @param mixed (string or XML_Tree) List of contents
     *
     * @return object XML_Tree
     * @access public
     */
    function makeXML($tag, $attribs = array(), $contents = array())
    {
        foreach ($attribs as $attrib => $value) {
            if (!is_null($value)) {
                if ($this->_options['quote']) {
                    $attribs[$attrib] = $this->quote($value);
                }
            }
        }
        $this->xml = new XML_Tree_Node(''.$tag, '', $attribs);
        $content = null;
        foreach ($contents as $c) {
            if (is_string($c)) {
                $content .= $c;
            }
        }
        if (!is_null($content)) {
            if ($this->_options['quote']) {
                $content = $this->quote($content);
            }
            $this->xml->content = $content;
        }
        foreach ($contents as $c) {
            if (is_object($c)) {
                $this->xml->addChild($c);
            }
        }
        return $this->xml;
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
    function quote($content)
    {
        $content = str_replace(array('&', '<', '>', '"', "'"),
                array('&amp;', '&lt;', '&gt;', '&quot;', '&apos;'), $content);
        return $content;
    }

    /**
     * Don't quote this content.
     *
     * To use only if the 'quote' is true
     * 
     * @param string Content to escape quoting
     *
     * @return string The content not quoted
     * @access  public
     */
    function noquote($content) {

        // Not yet possible with XML_Tree
        return $content;
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
        // Not yet possible with XML_Tree
        return $content;
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
        // Not yet possible with XML_Tree
        return $content;
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
        return $header.$this->xml->get();
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
        $tree = new XML_Tree();
        $tree->getTreeFromString($xml);
        return $tree->root;
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
        foreach ($data as $node) {
            if (is_string($node)) {
                $xml .= $node;
            } elseif (is_object($node)) {
                $xml .= $node->get();
            }
        }
        return $xml;
    }

}

?>
