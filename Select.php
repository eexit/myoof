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
class Select extends Element implements OptionHandler {
    
    /**
     *  The final compiled opbject result will go in this var
     *  @var string
     *  @access protected
     */
    protected $_output;
    
    /**
     *  Select content (Option objects or Optgroup objects)
     *  @var array
     *  @access protected
     */
    protected $_content = array();
    
    /**
     *  List of available attributes
     *  @var array
     *  @access protected
     */
    protected $_pattern = array(
        'class'     => null,
        'dir'       => 'ltr',
        'disabled'  => null,
        'id'        => null,
        'lang'      => null,
        'multiple'  => null,
        'name'      => null,
        'size'      => null,
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
        'multiple'  => 'multiple'
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
     *  Clones all content objects
     *  @access public
     */
    public function __clone() {
        
        // Clones all content objects
        if (!empty($this->_content)) {
            foreach ($this->_content as $contentId => $contentObject) {
                $this->_content[$contentId] = clone $contentObject;
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
                    $this->_content[] = $option;
                    break;
                case 'prepend' :
                    array_unshift($this->_content, $option);
                    break;
                default :
                    $this->_content[] = $option;
            }
            return $this;
        
        // Tests if the parameter is an Option recipy
        } else if (is_array($option) && !empty($option)) {
            
            // Adds the object depending of the adding way
            switch ($setType) {
                case 'append' :
                    $this->_content[] = new Option($option);
                    break;
                case 'prepend' :
                    array_unshift($this->_content, new Option($option));
                    break;
                default :
                    $this->_content[] = new Option($option);
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
        
        // Removes content objects
        $this->resetContent();
        
        // Adds Option object
        return $this->addOption($option);
    }
    
    /**
     *  Sets several Option objects
     *  @access public
     *  @param array $options An array of Option objects or option recipy
     *  @return mixed object $this | bool false
     */
    public function setOptions(array $options) {
        
        // Removes content objects
        $this->resetContent();
        
        // Adds Option objects
        return $this->addOptions($options);
    }
    
    /**
     *  Gets an Option object
     *  @access public
     *  @param int $optionId Option array index (in order it was inserted)
     *  @return mixed object Option object | bool false
     */
    public function getOption($optionId) {
        $optionId = abs(intval($optionId));
        
        // Tests if the requested option exists and if it's an Option object
        if (
            isset($this->_content[$optionId])
            && $this->_isOption($this->_content[$optionId])
        ) {
            return $this->_content[$optionId];
        }
        
        // Option doesn't exist, throws a warning message
        $this->_printError('Warning', 'Option ID #'
            . $optionId
            . ' does not exist or this is not an Option object');
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
        
        // Checks the parameter is not empty
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
        
        // Gets debugState
        $debugS = $this->debugState;
        
        // Disables debug if enabled
        if ($debugS) {
            $this->setDebugState(!$debugS);
        }
        
        // Option stack to return
        $options = array();
        
        // Loops on Select content
        if (!empty($this->_content)) {
            foreach ($this->_content as $objectId => $object) {
                if ($this->getOption($objectId)) {
                    $options[$objectId] = $object;
                }
            }
            
            // Enables back debugState
            if ($debugS) {
                $this->setDebugState($debugS);
            }
            
            return $options;
        }
        
        // Enables back debugState
        if ($debugS) {
            $this->setDebugState($debugS);
        }
        
        // There is not any element in _content
        $this->_printError('Warning', 'The Select object does not
            contain any Option or Optgroup objects');
        return false;
    }
    
    /**
     *  Unsets an Option object
     *  @access public
     *  @param int $optionId Option ID
     *  @param [ bool $reIndex = true ] Re-Indexes object stack after operation
     *  @return bool
     */
    public function unsetOption($optionId, $reIndex = true) {
        $optionId = abs(intval($optionId));
        
        // Tests if the requested ID is an Option object
        if (
            isset($this->_content[$optionId])
            && $this->_isOption($this->_content[$optionId])
        ) {
            unset($this->_content[$optionId]);
            
            if ($reIndex) {
                $this->reIndex();
            }
            
            return $this;
        }
        
        // Requested ID is not an Option or does not exist
        $this->_printError('Warning', 'Option ID #'
            . $optionId
            . ' does not exist or is not an Option object');
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
        
        // Checks that Select has entries
        if (!empty($this->_content)) {
            
            // Gets and unsets all Option objects
            $this->unsetOptions(@array_keys($this->getAllOptions()));
            return $this;
        }
        
        // Select is empty
        $this->_printError('Warning', 'There are not Options in Select
            content. Check there is a Select content');
        return false;
    }
    
    /**
     *  Adds an Optgroup object
     *  @access public
     *  @param mixed $optgroup Optgroup object | 
     *  array $optgroup Associative array of attributes
     *  @param [string $setType = 'append' The way to add objects]
     *  @return mixed object $this | bool false
     */
    public function addOptgroup($optgroup, $setType = 'append') {
        $setType = strtolower($setType);
        
        // Tests if parameter is an Optgroup object
        if ($this->_isOptgroup($optgroup)) {
            
            // Adds the object depending of the adding way
            switch ($setType) {
                case 'append' :
                    $this->_content[] = $optgroup;
                    break;
                case 'prepend' :
                    array_unshift($this->_content, $optgroup);
                    break;
                default :
                    $this->_content[] = $optgroup;
            }
            return $this;
        
        // Tests if the parameter is an Optgroup recipy
        } else if (is_array($optgroup) && !empty($optgroup)) {
            
            // Adds the object depending of the adding way
            switch ($setType) {
                case 'append' :
                    $this->_content[] = new Optgroup($optgroup);
                    break;
                case 'prepend' :
                    array_unshift($this->_content, new Optgroup($optgroup));
                    break;
                default :
                    $this->_content[] = new Optgroup($optgroup);
            }
            return $this;
        }
        
        // Parameter is not recongnized
        $this->_printError('Fatal error', 'Unable to set Optgroup object.
            Please, check your Optgroup parameter');
        return false;
    }
    
    /**
     *  Adds several Optgroup objects
     *  @access public
     *  @param array $optgroups Array of Optgroup object | 
     *  array $optgroups Array of associative array of attributes
     *  @param [string $setType = 'append' The way to add objects]
     *  @return mixed object $this | bool false
     */
    public function addOptgroups(array $optgroups, $setType = 'append') {
        
        // Array is not empty
        if (!empty($optgroups)) {
            foreach ($optgroups as $optgroup) {
                $this->addOptgroup($optgroup, $setType);
            }
            return $this;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There is not any Optgroups
            to add. Please, check your Optgroup parameter');
        return false;
    }
    
    /**
     *  Sets an Optgroup object
     *  @access public
     *  @param mixed $option Optgroup object | 
     *  array $option Associative array of attributes
     *  @return mixed object $this | bool false
     */
    public function setOptgroup($optgroup) {
        
        // Removes content objects
        $this->resetContent();
        
        // Adds the Optgroup
        return $this->addOptgroup($optgroup);
    }
    
    /**
     *  Sets several Optgroup objects
     *  @access public
     *  @param array $optgroups An array of Optgroup objects or option recipy
     *  @return mixed object $this | bool false
     */
    public function setOptgroups(array $optgroups) {
        
        // Removes content objects
        $this->resetContent();
        
        // Adds Optgroup objects
        return $this->addOptgroups($optgroups);
    }
    
    /**
     *  Gets an Optgroup object
     *  @access public
     *  @param int $optionId Optgroup array index (in order it was inserted)
     *  @return mixed object Optgroup object | bool false
     */
    public function getOptgroup($optgroupId) {
        $optgroupId = abs(intval($optgroupId));
        
        // Tests if the requested Optgroup exists and if it's an Optgroup object
        if (
            isset($this->_content[$optgroupId])
            && $this->_isOptgroup($this->_content[$optgroupId])
        ) {
            return $this->_content[$optgroupId];
        }
        
        // Option doesn't exist, throws a warning message
        $this->_printError('Warning', 'Optgroup ID #'
            . $optgroupId
            . ' does not exist or this is not an Optgroup object');
        return false;
    }
    
    /**
     *  Gets several Optgroup objects. Array keys are object ID
     *  @access public
     *  @param array $optgroups Array of option index id
     *  @return mixed array $optgroups Associative array of wanted Optgroups
     *  | bool false
     */
    public function getOptgroups(array $optgroups) {
        if (!empty($optgroups)) {
            foreach ($optgroups as $optgroupId) {
                
                // Gets Optgroup object
                $optgroups[$optgroupId] = $this->getOptgroup($optgroupId);
            }
            return $optgroups;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There are not Optgroup objects to get.
            Please check your parameter content');
        return false;
    }
    
    /**
     *  Gets all Optgroup objects
     *  @access public
     *  @return mixed array $optgroups All Select optgroups | bool false
     */
    public function getAllOptgroups() {
        
        // Gets debugState
        $debugS = $this->debugState;
        
        // Disables debug if enabled
        if ($debugS) {
            $this->setDebugState(!$debugS);
        }
        
        // Optgroup stack to return
        $optgroups = array();
        
        // Loops on Select content
        if (!empty($this->_content)) {
            foreach ($this->_content as $objectId => $object) {
                if ($this->getOptgroup($objectId)) {
                    $optgroups[$objectId] = $object;
                }
            }
            
            // Enables back debugState
            if ($debugS) {
                $this->setDebugState($debugS);
            }
            
            return $optgroups;
        }
        
        // Enables back debugState
        if ($debugS) {
            $this->setDebugState($debugS);
        }
        
        // There is not any element in _content
        $this->_printError('Warning', 'The Select object does not
            contain any Option or Optgroup objects');
        return false;
    }
    
    /**
     *  Unsets an Optgroup object
     *  @access public
     *  @param int $optgroupId The Optgroup ID to unset
     *  @param [ bool $reIndex = true ] Re-Indexes object stack after operation
     *  @return mixed object $this | bool false
     */
    public function unsetOptgroup($optgroupId, $reIndex = true) {
        $optgroupId = abs(intval($optgroupId));
        
        // Tests if the requested ID is an Optgroup object
        if (
            isset($this->_content[$optgroupId])
            && $this->_isOptgroup($this->_content[$optgroupId])
        ) {
            unset($this->_content[$optgroupId]);
            
            if ($reIndex) {
                $this->reIndex();
            }
            
            return $this;
        }
        
        // Requested ID is not an Optgroup or does not exist
        $this->_printError('Warning', 'Optgroup ID #'
            . $optgroupId
            . ' does not exist or is not an Optgroup object');
            return false;
    }
    
    /**
     *  Unsets several Optgroup objects
     *  @access public
     *  @param array $optgroups Array of Optgroups ID
     *  @return mixed object $this | bool false
     */
    public function unsetOptgroups(array $optgroups) {
        
        // Tests if the given parameter is not empty
        if (!empty($optgroups)) {
            
            // Launches unset Option proccess for each array entry
            foreach ($optgroups as $optgroupId) {
                $this->unsetOptgroup($optgroupId, false);
            }
            
            $this->reIndex();
            return $this;
        }
        
        // Parameter is empty
        $this->_printError('Warning', 'There is not any Optgroup objects
            to unset. Please, check your Optgroup parameter');
        return false;
    }
    
    /**
     *  Unsets all Optgroup objects
     *  @access public
     *  @return mixed object $this | bool false
     */
    public function resetOptgroups() {
        
        // Checks that Select has entries
        if (!empty($this->_content)) {
            
            // Gets and unsets all Optgroup objects
            $this->unsetOptgroups(@array_keys($this->getAllOptgroups()));            
            return $this;
        }
        
        // Select is empty
        $this->_printError('Warning', 'There are not Optgroups in Select
            content. Check there is a Select content');
        return false;
    }
    
    /**
     *  Gets Select content (objects)
     *  @access public
     *  @return array $_content
     */
    public function getContent() {
        return $this->_content;
    }
    
    /**
     *  Unsets Select content (objects)
     *  @access public
     *  @return object $this
     */
    public function resetContent() {
        $this->_content = array();
        return $this;
    }
    
    /**
     *  Re-Indexes stack objects
     *  @access public
     *  @return object $this
     */
    public function reIndex() {
        $this->_content = @array_values($this->_content);
        return $this;
    }
    
    /**
     *  Gets final source code. Also called by {@link Element::__toString()}
     *  @final
     *  @access public
     *  @return mixed string The generated object source code | bool false
     */
    final public function getOutput() {
        
        // Checks if there is a Select content
        if (empty($this->_content)) {
            $this->_printError('Fatal error', 'Unable to create a Select 
                object without at least one Option or Optgroup object');
            return false;
        }
        
        if ($this->_buildSelect()) {
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
     *  Tests if the given parameter is an instance of Optgroup class
     *  @access private
     *  @param mixed $optgroup
     *  @return bool
     */
    private function _isOptgroup($optgroup) {
        return is_object($optgroup) && $optgroup instanceof Optgroup;
    }
    
    /**
     *  Generates final source code. Called by {@link Select::getOutput()}
     *  @access private
     *  @return bool true
     */
    private function _buildSelect() {
        $this->_output = '<select ';
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
        
        // Generates and appends Select content
        foreach ($this->_content as $object) {
            
            // Adds indent if enabled
            if ($this->indentState) {
                $this->_output .= $object->setIndentChar($this->indentChar)
                                         ->getOutput()
                               . "\n"
                               . $this->indentChar;
            } else {
                $this->_output .= $object->setIndentState(false)->getOutput();
            }
        }
        
        // Adds indent if enabled
        if ($this->indentState) {
            $this->_output = substr(
                $this->_output,
                0,
                - strlen($this->indentChar)
            );
        }
        
        $this->_output .= '</select>';
        return true;
    }
}
?>