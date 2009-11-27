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
 *  @abstract
 *  @package MyOOF
 */
abstract class Element {
    
    /**
     *  Error reporting state (default = true)
     *  @var bool
     *  @access public
     */
    public $debugState = false;
    
    /**
     *  Indentation state (default = true)
     *  @var bool
     *  @access public
     */
    public $indentState = true;
    
    /**
     *  Indentation pattern (default = 4 spaces)
     *  @var string
     *  @access public
     */
     public $indentChar = '    ';
    
    /**
     *  MyOOF allowed working classes
     *  @var array
     *  @access private
     */
    private $_availableChilds = array(
        'Input',
        'Textarea',
        'Select',
        'Option',
        'Optgroup'
    );
    
    /**
     *  JavaScript event attribute pattern
     *  @var array
     *  @access private
     */
    private $_eventPattern = array(
        'onclick'       => null,
        'ondblclick'    => null,
        'onkeydown'     => null,
        'onkeyup'       => null,
        'onkeypress'    => null,
        'onmousedown'   => null,
        'onmouseup'     => null,
        'onmousemove'   => null,
        'onmouseover'   => null,
        'onmouseout'    => null,
        'onload'        => null,
        'onunload'      => null,
        'onblur'        => null,
        'onchange'      => null,
        'onfocus'       => null,
        'onsubmit'      => null,
        'onselect'      => null
    );
    
    /**
     *  Most common attributes where value should be type-checked
     *  @var array
     *  @access private
     */
    private $_specificAttributes = array(
        'int'       => array('cols', 'maxlength', 'rows', 'size', 'tabindex'),
        'string'    => array('lang', 'xml:lang'),
        'mixed'     => array('accesskey')
    );
    
    /**
     *  Gets final source code
     *  @abstract
     *  @access public
     */
    abstract public function getOutput();
    
    /**
     *  Must be called by extended classes. Checks if the extended class is
     *  declared as MyOOF internal classes ({@link Element::$_availableChilds}). 
     *  If yes it appends {@link Element::$_evenPattern} to the extended 
     *  $_pattern class.
     *  @access public
     *  @param  object $child Instance of a child
     *  @return bool
     */
    public function __construct($child) {
        
        // If the working child is allowed
        if (in_array(get_class($child), $this->_availableChilds, true)) {
            
            // Appends JavaScript events to the child pattern
            $this->_pattern = array_merge(
                                $this->_pattern,
                                $this->_eventPattern
                            );
            return true;
        }
        
        // Child is not allowed, throws a fatal error
        $this->_printError('Fatal error', 'Element::'
            . get_class($child)
            . ' child is not allowed to be executed. 
              Check Element::$_availableChilds for application configuration');
        return false;
    }
    
    /**
     *  Outputs an Object
     *  @access public
     *  @return mixed string The generated object source code | bool false
     */
    public function __toString() {
        
        // If the build proccess succeed
        if ($this->getOutput()) {
            
            // Outputs the object generated code
            return $this->getOutput();
        
        // Throws an error and return false
        } else {
            $this->_printError('Fatal error', 'Unable to compile the Element::'
                . get_class($this)
                . ' pattern. Please check your given attribute set');
            return false;
        }
    }
    
    /**
     *  Sets an Object attribute value
     *  @access public
     *  @param string $attr The attribute name
     *  @param mixed $value The attribute value to set
     *  @return bool
     */
    public function __set($attr, $value) {
        
        // The attribute name exists in the object attributes
        if (@array_key_exists($attr, $this->_pattern)) {
            
            // The value is null, we want to unset the attribute
            if ($value === null) {
                
                // Unset process
                $this->__unset($attr);
                return true;
            
            // Checks possible constraints on the attribute value
            } else if (
                $this->_setDefaultValue($attr, $value)
                || $this->_setMixedValue($attr, $value)
                || $this->_setStringValue($attr, $value)
                || $this->_setIntValue($attr, $value)
            ) {
                return true;
            
            // There is not constraint on the attribute value
            } else {
                $this->_pattern[$attr] = $value;
                return true;
            }
        }
        
        // Attribute not found
        $this->_printError('Warning', 'There is no <tt>'
            . $attr
            . '</tt> attribute in '
            . get_class($this)
            . ' XHTML object');
        return false;
    }
    
    /**
     *  Tests if is set an Object attribute value
     *  @access public
     *  @param  string $attr Attribute name
     *  @return bool
     */
    public function __isset($attr) {
        
        // If the attribute exists or the class property exists
        return isset($this->_pattern[$attr]);
    }
    
    /**
     *  Gets an Object attribute value
     *  @access public
     *  @param  string $attr Attribute name to get
     *  @return mixed The attribute value | bool false
     */
    public function __get($attr) {
        
        // Resquest is an object attribute
        if (@array_key_exists($attr, $this->_pattern)) {
            return $this->_pattern[$attr];
        }
        
        // Attribute not found
        $this->_printError('Warning', 'There is no <tt>'
            . $attr
            . '</tt> attribute in '
            . get_class($this)
            . ' XHTML object');
        return false;
    }
    
    /**
     *  Unsets an Object attribute value
     *  @access public
     *  @param string $attr The attribute to reset
     *  @return void
     */
    public function __unset($attr) {
        
        // Request is an object attribute
        if (@array_key_exists($attr, $this->_pattern)) {
            $this->_pattern[$attr] = null;
        }
    }
    
    /**
     *  Changes debug state
     *  @access public
     *  @param bool $state The debug state
     *  @return object $this
     */
    public function setDebugState($state) {
        $this->debugState = (bool) $state;
        return $this;
    }
    
    /**
     *  Changes indentation state
     *  @access public
     *  @param bool $state The indent state
     *  @return object $this
     */
    public function setIndentState($state) {
         $this->indentState = (bool) $state;
         return $this;
    }
    
    /**
     *  Changes indentation pattern
     *  @access public
     *  @param string $char The indent character to use
     *  @return object $this
     */
    public function setIndentChar($char) {
        if (!empty($char)) {
            $this->indentChar = (string) $char;
            return $this;
        }
        
        // Character is empty
        $this->_printError('Error', 'Indent character must not be empty');
        return false;
    }
    
    /**
     *  Sets an Object attribute value
     *  @access public
     *  @param string $attr The attribute name
     *  @param mixed $value The attribute value
     *  @return object $this
     */
    public function setAttribute($attr, $value) {
        
        // Set attribute proccess
        $this->$attr = $value;
        return $this;
    }
    
    /**
     *  Sets several Object attribute values
     *  @access public
     *  @param array $attrs Associative array of attributes
     *  @return mixed object $this | bool false
     */
    public function setAttributes(array $attrs) {
        
        // Tests if the parameter is empty
        if (!empty($attrs)) {
            foreach ($attrs as $attr => $value) {
                
                // Set attribute proccess
                $this->$attr = $value;
            }
            return $this;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There are not attributes to set. 
            Please check your parameter content');
        return false;
    }
    
    /**
     *  Gets an Object attribute value
     *  @access public
     *  @param string $attr The attibute name wanted
     *  @return mixed The attribute value
     */
    public function getAttribute($attr) {
        return $this->__get($attr);
    }
    
    /**
     *  Gets several Object attribute values
     *  @access public
     *  @param array $attrs An array which contain attribute name
     *  @return mixed array An associative array | bool false
     */
    public function getAttributes(array $attrs) {
        
        // If the parameter is not empty
        if (!empty($attrs)) {
            foreach ($attrs as $attr) {
                
                // Get attribute proccess
                $attrs[$attr] = $this->__get($attr);
            }
            return $attrs;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There are not attributes to get.
            Please check your parameter content');
        return false;
    }
    
    /**
     *  Gets all Object attribute values (even empty ones)
     *  @access public
     *  @return array Working child class attribute associative array
     */
    public function getAllAttributes() {
        return $this->_pattern;
    }
    
    /**
     *  Unsets an Object attribute value
     *  @access public
     *  @param string $attr The attribute name
     *  @return object $this
     */
    public function unsetAttribute($attr) {
        
        // Unset attribute proccess
        $this->__unset($attr);
        return $this;
    }
    
    /**
     *  Unsets several Object attribute values
     *  @access public
     *  @param array $attrs Array of attribute names
     *  @return mixed object $this | bool false
     */
    public function unsetAttributes(array $attrs) {
        
        // If the parameter is not empty
        if (!empty($attrs)) {
            foreach ($attrs as $attr) {
                
                // Unset attribute proccess
                $this->__unset($attr);
            }
            return $this;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There are not attributes to unset.
            Please check your parameter content');
        return false;
    }
    
    /**
     *  Unsets all Object attribute values
     *  @access public
     *  @return object $this
     */
    public function resetAttributes() {
        foreach ($this->_pattern as $attr => $value) {
            
            // Sets all attribute value to null
            $this->__unset($attr);
        }
        return $this;
    }
    
    /**
     *  Outputs MyOOF errors/warnings when {@link Element::$debugState} is
     *  set to TRUE
     *  @access protected
     *  @param string $errorType The error level ("Notice", "Warning" or
     *  "Fatal error")
     *  @param string $errorMessage The error content
     */
    protected function _printError($errorType, $errorMessage) {
        switch ($errorType) {
            
            // Notice error type
            case 'Notice' :
                if ($this->debugState) {
                    echo '<p><strong>Notice:</strong> '
                        . $errorMessage
                        . '.</p>';
                }
                break;
            
            // Warning error type
            case 'Warning' :
                if ($this->debugState) {
                    echo '<p><strong style="color:#f60;">Warning:</strong> '
                        . $errorMessage
                        . '.</p>';
                }
                break;
            
            // Error or Fatal error type
            case 'Error' :
            case 'Fatal error' :
                if ($this->debugState) {
                    exit('<p><strong style="color:#f00;">Fatal error:</strong> '
                        . $errorMessage
                        . '.</p>');
                }
                exit;
                break;
        }
    }
    
    /**
     *  Tests Object attribute name has a speficied value
     *  @access private
     *  @param string $attr The attribute name
     *  @param mixed $value The attribute value
     *  @return bool
     */
    private function _setDefaultValue($attr, $value) {
        
        // If the attribute has a default value
        if (
            isset($this->_defaultValues)
            && @array_key_exists($attr, $this->_defaultValues)
        ) {
            
            // If the attribute could have several default values and
            // if a value matches
            if (
                is_array($this->_defaultValues[$attr])
                && in_array($value, $this->_defaultValues[$attr], true)
            ) {
                
                // Succeed, value assignation
                $this->_pattern[$attr] = (string) $value;
                return true;
            
            // Or no several value possible, tests if the value matches
            } else if (in_array($value, $this->_defaultValues, true)) {
                
                // Succeed, value assignation
                $this->_pattern[$attr] = (string) $value;
                return true;
            }
            
            // Tests failed, throws a warning message
            $this->_printError('Warning', '<q>'
                . $value
                . '</q> is not a valid value for <tt>'
                . $attr
                . '</tt> XHTML '
                . get_class($this)
                . ' attribute');
        }
        return false;
    }
    
    /**
     *  Tests string-type-check needed Object attribute value
     *  @access private
     *  @param string $attr The attribute name
     *  @param mixed $value The attribute value
     *  @return bool
     */
    private function _setStringValue($attr, $value) {
        
        // The attribute name exists in string array
        if (in_array($attr, $this->_specificAttributes['string'], true)) {
            
            // Tests if the value is a correct string value
            if (preg_match('/^[a-z-]{1,}$/i', $value)) {
                
                // Test succeed, value assignation
                $this->_pattern[$attr] = (string) $value;
                return true;
            }
            
            // Tests failed, throws a warning message
            $this->_printError('Warning', '<q>'
                . $value
                . '</q> is not a valid value for <tt>'
                . $attr
                . '</tt> XHTML '
                . get_class($this)
                . ' attribute');
        }
        return false;
    }
    
    /**
     *  Tests integer-type-check needed Object attribute value
     *  @access private
     *  @param string $attr The attribute name
     *  @param mixed $value The attribute value
     *  @return bool
     */
    private function _setIntValue($attr, $value) {
        
        // The attribute name exists in int array
        if (in_array($attr, $this->_specificAttributes['int'], true)) {
            
            // Tests if the valus is numeric
            if (is_numeric($value) && $value >= 0) {
                
                // Test succeed, value assignation
                $this->_pattern[$attr] = (int) $value;
                return true;
            }
            
            // Tests failed, throws a warning message
            $this->_printError('Warning', '<q>'
                . $value
                . '</q> is not a valid value for <tt>'
                . $attr
                . '</tt> XHTML '
                . get_class($this)
                . ' attribute');
        }
        return false;
    }
    
    /**
     *  Tests mixed-type-check needed Object attribute value
     *  @access private
     *  @param string $attr The attribute name
     *  @param mixed $value The attribute value
     *  @return bool
     */
    private function _setMixedValue($attr, $value) {
        
        // The attribute name exists in mixed array
        if (in_array($attr, $this->_specificAttributes['mixed'])) {
            
            // Tests the value
            if (
                (is_numeric($value) && $value >= 0)
                || preg_match('/^[a-z]{1}$/', $value)
            ) {
                
                // Test succeed, value assignation
                $this->_pattern[$attr] = (string) $value;
                return true;
            }
            
            // Tests failed, throws a warning message
            $this->_printError('Warning', '<q>'
                . $value
                . '</q> is not a valid value for <tt>'
                . $attr
                . '</tt> XHTML '
                . get_class($this)
                . ' attribute');
        }
        return false;
    }
}
?>