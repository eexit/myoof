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
class Input extends Element {
    
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
        'accept'    => null,
        'accesskey' => null,
        'alt'       => null,
        'checked'   => null,
        'class'     => null,
        'dir'       => 'ltr',
        'disabled'  => null,
        'id'        => null,
        'lang'      => null,
        'maxlength' => null,
        'name'      => null,
        'readonly'  => null,
        'size'      => null,
        'src'       => null,
        'style'     => null,
        'tabindex'  => null,
        'title'     => null,
        'type'      => 'text',
        'usemap'    => null,
        'value'     => '',
        'xml:lang'  => null,
        'xmlns'     => null
    );
    
    /**
     *  List of default attribute values
     *  @var array
     *  @access protected
     */
    protected $_defaultValues = array(
        'checked'   => 'checked',
        'dir'       => array('ltr', 'rtl'),
        'disabled'  => 'disabled',
        'readonly'  => 'readonly',
        'type'      => array('button', 'checkbox', 'file', 'hidden', 'image',
                             'password', 'radio', 'reset', 'submit', 'text')
    );
    
    /**
     *  Instanciates the self class if allowed in
     *  {@link Element::$_availableChilds}
     *  @final
     *  @access public
     *  @param [array $config = null Associative array of attributes]
     */
    final public function __construct(array $config = null) {
        if (parent::__construct($this)) {
            if (!empty($config)) {
                $this->setAttributes($config);
            }
        }
    }
    
    /**
     *  Gets final source code. Also called by {@link Element::__toString()}
     *  @final
     *  @access public
     *  @return mixed string The generated object source code | bool false
     */
    final public function getOutput() {
        if ($this->_buildInput()) {
            return $this->_output;
        }
        return false;
    }
    
    /**
     *  Generates final source code. Called by {@link Input::getOutput()}
     *  @access private
     *  @return bool true
     */
    private function _buildInput() {
        $this->_output = '<input ';
        foreach ($this->_pattern as $attr => $value) {
            if (!is_null($value)) {
                $this->_output .= $attr . '="' . $value . '" ';
            }
        }
        $this->_output .= '/>';
        return true;
    }
}
?>