<?php
/*
*   ###########
*   #__________#
*   __________#
*   ________#
*   _____###_____²xiT development
*   _________#
*   ___________#
*   #__________#
*   _#________#
*   __#______#
*   ____####
*
*/

/**
*   @package    MyOOF
*   @version    2.00
*   @author     Joris Berthelot <admin@eexit.net>
*   @copyright  Copyright (c) 2008, Joris Berthelot
*   @license    http://www.opensource.org/licenses/mit-license.php MIT Licence
*/

/**
 *  @package MyOOF
 */
class Textarea extends Element {
    
    /**
     *  The Textarea content
     *  @var string
     *  @access public
     */
    public $content = '';
    
    /**
     *  The final compiled opbject result will go in this var
     *  @var string
     *  @access protected
     */
    protected $_output;
    
    /**
     *  List of available attributes
     *  @var array
     *  @access protected
     */
    protected $_pattern = array(
        'accesskey' => null,
        'class'     => null,
        'cols'      => 40,
        'dir'       => 'ltr',
        'disabled'  => null,
        'id'        => null,
        'lang'      => null,
        'name'      => null,
        'readonly'  => null,
        'rows'      => 6,
        'style'     => null,
        'tabindex'  => null,
        'title'     => null,
        'xml:lang'  => null,
        'xmlns'     => null
    );
    
    /**
     *  List of default attribute values
     *  @var array
     *  @access protected
     */
    protected $_defaultValues = array(
        'dir'       => array('ltr', 'rtl'),
        'disabled'  => 'disabled',
        'readonly'  => 'readonly'
    );
    
    /**
     *  Instanciates the self class if allowed in
     *  {@link Element::$_availableChilds}
     *  @final
     *  @access public
     *  @param [array $config = null Associative array of attributes]
     *  @param [string $content = '' Textarea content]
     */
    final public function __construct(array $config = null, $content = '') {
        if (parent::__construct($this)) {
            
            // Sets content
            $this->setContent($content);
            
            if (!empty($config)) {
                $this->setAttributes($config);
            }
        }
    }
    
    /**
     *  Sets the Textarea content ({@link Textarea::$content})
     *  @access public
     *  @param string $content Content to set (can be null or empty)
     *  @return object $this
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    /**
     *  Gets the Textarea content ({@link Textarea::$content})
     *  @access public
     *  @return string $content Textarea content
     */
    public function getContent() {
        return $this->content;
    }
    
    /**
     *  Unsets the Textarea content ({@link Textarea::$content})
     *  returns the current instance.
     *  @access public
     *  @return object $this
     */
    public function unsetContent() {
        $this->content = '';
        return $this;
    }
    
    /**
     *  Gets final source code. Also called by {@link Element::__toString()}
     *  @final
     *  @access public
     *  @return mixed string The generated object source code | bool false
     */
    final public function getOutput() {
        if ($this->_buildTextarea()) {
            return $this->_output;
        }
        return false;
    }
    
    /**
     *  Generates final source code. Called by {@link Textarea::getOutput()}
     *  @access private
     *  @return bool true
     */
    private function _buildTextarea() {
        $this->_output = '<textarea ';
        foreach ($this->_pattern as $attr => $value) {
            if (!is_null($value)) {
                $this->_output .= $attr . '="' . $value . '" ';
            }
        }
        
        if (empty($this->content)) {
            $this->_output = substr($this->_output, 0, -1) . '></textarea>';
        } else {
            $this->_output = substr($this->_output, 0, -1)
                . '>'
                . $this->content
                . '</textarea>';
        }
        return true;
    }
}
?>