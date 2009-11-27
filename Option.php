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
class Option extends Element {
    
    /**
     *  The Option content (visible value)
     *  @var string
     *  @access public
     */
    public $caption = '';
    
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
    public $_pattern = array(
        'class'     => null,
        'dir'       => 'ltr',
        'disabled'  => null,
        'id'        => null,
        'label'     => null,
        'lang'      => null,
        'selected'  => null,
        'style'     => null,
        'title'     => null,
        'value'     => null,
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
        'selected'  => 'selected'
    );
    
    /**
     *  Instanciates the self class if allowed in
     *  {@link Element::$_availableChilds}
     *  @final
     *  @access public
     *  @param [array $config = null Associative array of attributes]
     *  @param [string $caption = '' Option caption]
     */
    final public function __construct(array $config = null, $caption = '') {
        if (parent::__construct($this)) {
            
            // Sets caption
            $this->setCaption($caption);
            
            if (!empty($config)) {
                $this->setAttributes($config);
            }
        }
    }
    
    /**
     *  Sets the Option caption ({@link Option::$caption})
     *  @access public
     *  @param string $caption Caption to set
     *  @return object $this
     */
    public function setCaption($caption) {
        $this->caption = $caption;
        return $this;
    }
    
    /**
     *  Gets the Option caption ({@link Option::$caption})
     *  @access public
     *  @return string $caption Option caption
     */
    public function getCaption() {
        return $this->caption;
    }
    
    /**
     *  Unsets the Option caption ({@link Option::$caption})
     *  @access public
     *  @return object $this
     */
    public function unsetCaption() {
        $this->caption = '';
        return $this;
    }
    
    /**
     *  Gets final source code. Also called by {@link Element::__toString()}
     *  @final
     *  @access public
     *  @return mixed string The generated object source code | bool false
     */
    final public function getOutput() {
        if ($this->_buildOption()) {
            return $this->_output;
        }
        
        // An error occured
        $this->_printError('Fatal error', 'Unable to compile '
            . __CLASS__
            . '::_pattern. Please check your given attribute set');
        return false;
    }
    
    /**
     *  Generates final source code. Called by {@link Option::getOutput()}
     *  @access private
     *  @return bool true
     */
    private function _buildOption() {
        $this->_output = '<option ';
        foreach ($this->_pattern as $attr => $value) {
            if (!is_null($value)) {
                $this->_output .= $attr . '="' . $value . '" ';
            }
        }
        
        if (empty($this->caption)) {
            $this->_output = substr($this->_output, 0, -1) . '></option>';
        } else {
            $this->_output = substr($this->_output, 0, -1)
                . '>'
                . $this->caption
                . '</option>';
        }
        return true;
    }
}
?>