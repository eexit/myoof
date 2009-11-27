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
class Optgroup extends Element implements OptionHandler {
    
    /**
     *  The final compiled object result will go in this var
     *  @var string
     *  @access protected
     */
    protected $_output;
    
    /**
     *  Optgroup content
     *  @var array
     *  @access protected
     */
    protected $_options = array();
    
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
        'label'     => '',
        'lang'      => null,
        'style'     => null,
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
        'disabled'  => 'disabled'
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
     *  Clones all Option objects
     *  @access public
     */
    public function __clone() {
        
        // Clones all Option objects
        if (!empty($this->_options)) {
            foreach ($this->_options as $optionId => $optionObject) {
                $this->_options[$optionId] = clone $optionObject;
            }
        }
    }
    
    /**
     *  Adds an Option object
     *  @access public
     *  @param mixed $option Option object | 
     *  array $option Associative array of attributes
     *  @param [string $setType = 'append' The way to add objects]
     *  @return mixed object $this | bool false
     */
    public function addOption($option, $setType = 'append') {
        $setType = strtolower($setType);
        
        // Tests if parameter is an Option object
        if ($this->_isOption($option)) {
            
            // Adds the object depending of the adding way
            switch ($setType) {
                case 'append' :
                    $this->_options[] = $option;
                    break;
                case 'prepend' :
                    array_unshift($this->_options, $option);
                    break;
                default :
                    $this->_options[] = $option;
            }
            return $this;
        
        // Tests if the parameter is an Option recipy
        } else if (is_array($option) && !empty($option)) {
            
            // Adds the object depending of the adding way
            switch ($setType) {
                case 'append' :
                    $this->_options[] = new Option($option);
                    break;
                case 'prepend' :
                    array_unshift($this->_options, new Option($option));
                    break;
                default :
                    $this->_options[] = new Option($option);
            }
            return $this;
        }
        
        // Parameter is not recongnized
        $this->_printError('Warning', 'Unable to add Option object.
            Please, check your Option parameter');
        return false;
    }
    
    /**
     *  Adds several Option objects
     *  @access public
     *  @param array $options Array of Option object | 
     *  array $options Array of associative array of attributes
     *  @param [string $setType = 'append' The way to add objects]
     *  @return mixed object $this | bool false
     */
    public function addOptions(array $options, $setType = 'append') {
        
        // Array is not empty
        if (!empty($options)) {
            foreach ($options as $option) {
                $this->addOption($option, $setType);
            }
            return $this;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There is not any Option objects
            to add. Please, check your Option parameter');
        return false;
    }
    
    /**
     *  Sets an Option object
     *  @access public
     *  @param mixed $option Option object | 
     *  array $option Associative array of attributes
     *  @return mixed object $this | bool false
     */
    public function setOption($option) {
        
        // Removes all Option objects
        $this->resetOptions();
        
        // Tests if parameter is an Option object
        if ($this->_isOption($option)) {
            $this->_options[] = $option;
            return $this;
        
        // Tests if the parameter is an Option recpity
        } else if (is_array($option) && !empty($option)) {
            $this->_options[] = new Option($option);
            return $this;
        }
        
        // Parameter is not recongnized
        $this->_printError('Warning', 'Unable to set Option object.
            Please, check your Option parameter');
        return false;
    }
    
    /**
     *  Sets several Option objects
     *  @access public
     *  @param array $options An array of Option objects or option recipy
     *  @return mixed object $this | bool false
     */
    public function setOptions(array $options) {
        $new_options = array();
        
        // Removes all Option objects
        $this->resetOptions();
        
        // If parameter is not empty
        if (!empty($options)) {
            foreach ($options as $option) {
                
                // Array item is an Option object
                if ($this->_isOption($option)) {
                    $new_options[] = $option;
                
                // Array item is an Option "recipy" array
                } else if (is_array($option) && !empty($option)) {
                    $new_options[] = new Option($option);
                
                // Show a fatal error if unable to create
                } else {
                    $this->_printError('Warning', 'Unable to set Option object.
                        Please check your Option parameter');
                }
            }
            $this->_options = $new_options;
            return $this;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There is not any Option objects
            to set. Please, check your Option parameter');
        return false;
    }
    
    /**
     *  Gets an Option object
     *  @access public
     *  @param int $optionId Option array index (in order it was inserted)
     *  @return mixed object Option object | bool false
     */
    public function getOption($optionId) {
        $optionId = abs(intval($optionId));
        
        // Tests if the requested option exists
        if (isset($this->_options[$optionId])) {
            return $this->_options[$optionId];
        }
        
        // Option doesn't exist, throws a warning message
        $this->_printError('Warning', 'Option ID #'
            . $optionId
            . ' does not exist');
        return false;
    }
    
    /**
     *  Gets several Option objects. Array keys are object ID
     *  @access public
     *  @param array $optionIds Array of option index id
     *  @return mixed array $option Associative array of wanted Options
     *  | bool false
     */
    public function getOptions(array $optionIds) {
        
        // If parameter is not empty
        if (!empty($optionIds)) {
            foreach ($optionIds as $optionId) {
                
                // Gets Option object
                $optionIds[$optionId] = $this->getOption($optionId);
            }
            return $optionIds;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There are not Option objects to get.
            Please check your parameter content');
        return false;
    }
    
    /**
     *  Gets all Option objects
     *  @access public
     *  @return array $_options All Option objects
     */
    public function getAllOptions() {
        return $this->_options;
    }
    
    /**
     *  Unsets an Option object
     *  @access public
     *  @param int $optionId Option ID
     *  @param [bool $reIndex = true] Re-index stack after operation
     *  @return bool
     */
    public function unsetOption($optionId, $reIndex = true) {
        $optionId = abs(intval($optionId));
        
        // Tests if the requested ID is an Option object
        if (isset($this->_options[$optionId])) {
            unset($this->_options[$optionId]);
            
            if ($reIndex) {
                $this->reIndex();
            }
            
            return $this;
        }
        
        // Requested ID is not an Option or does not exist
        $this->_printError('Warning', 'Option ID #'
            . $optionId
            . ' does not exist');
            return false;
    }
    
    /**
     *  Unsets several Option objects
     *  @access public
     *  @param array $optionIds Array of Option ID
     *  @return mixed object $this | bool false
     */
    public function unsetOptions(array $optionIds) {
        
        // Tests if the given parameter is not empty
        if (!empty($optionIds)) {
            
            // Launches unset Option proccess for each array entry
            foreach ($optionIds as $optionId) {
                $this->unsetOption($optionId, false);
            }
            
            $this->reIndex();
            return $this;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There is not any Option objects
            to unset. Please, check your Option parameter');
        return false;
    }
    
    /**
     *  Unsets all Option objects
     *  @access public
     *  @return object $this
     */
    public function resetOptions() {
        $this->_options = array();
        return $this;
    }
    
    /**
     *  Re-Indexes stack objects
     *  @access public
     *  @return object $this
     */
    public function reIndex() {
        $this->_options = @array_values($this->_options);
        return $this;
    }
    
    /**
     *  Gets final source code. Also called by {@link Element::__toString()}
     *  @final
     *  @access public
     *  @return mixed string The generated object source code | bool false
     */
    final public function getOutput() {
        
        // Checks if there are Option objects
        if (empty($this->_options)) {
            $this->_printError('Fatal error', 'Unable to create an Optgroup 
                object without at least one Option object');
            return false;
        }
        
        if ($this->_buildOptgroup()) {
            return $this->_output;
        }
        return false;
    }
    
    /**
     *  Tests if the given parameter is an instance of Option class
     *  @access private
     *  @param mixed $option
     *  @return bool
     */
    private function _isOption($option) {
        return is_object($option) && $option instanceof Option;
    }
    
    /**
     *  Generates final source code. Called by {@link Optgroup::getOutput()}
     *  @access private
     *  @return bool true
     */
    private function _buildOptgroup() {
        $this->_output = '<optgroup ';
        foreach ($this->_pattern as $attr => $value) {
            if (!is_null($value)) {
                $this->_output .= $attr . '="' . $value . '" ';
            }
        }
        
        // Removes the last space
        $this->_output = substr($this->_output, 0, -1) . '>';
        
        // Adds indent if enabled
        if ($this->indentState) {
            $this->_output .= "\n" . $this->indentChar;
        }
        
        // Generates and appends Option objects
        foreach ($this->_options as $option) {
            
            // Adds indent if enabled
            if ($this->indentState) {
                $this->_output .= $this->indentChar
                               . $option->getOutput()
                               . "\n"
                               . $this->indentChar;
            } else {
                $this->_output .= $option->getOutput();
            }
        }
        
        // Adds indent if enabled
        if ($this->indentState) {
            $this->_output = substr(
                $this->_output,
                0,
                - strlen($this->indentChar)
            );
            $this->_output .= $this->indentChar;
        }
        
        $this->_output .= '</optgroup>';
        return true;
    }
}
?>