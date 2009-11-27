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
interface OptionHandler {
    /**
     *  Clones all Options objects
     *  @access public
     */
    public function __clone();
    
    /**
     *  Adds an Option object
     *  @access public
     *  @param mixed $option Option object | 
     *  array $option Associative array of attributes
     *  @param [string $setType = 'append' The way to add objects]
     *  @return mixed object $this | bool false
     */
    public function addOption($option, $setType = 'append');
    
    /**
     *  Adds several Option objects
     *  @access public
     *  @param array $optionsIds Array of Option object | 
     *  array $optionsIds Array of associative array of attributes
     *  @param [string $setType = 'append' The way to add objects]
     *  @return mixed object $this | bool false
     */
    public function addOptions(array $options, $setType = 'append');
    
    /**
     *  Sets an Option object
     *  @access public
     *  @param mixed $option Option object | 
     *  array $option Associative array of attributes
     *  @return mixed object $this | bool false
     */
    public function setOption($option);
    
    /**
     *  Sets several Option objects
     *  @access public
     *  @param array $optionsIds An array of Option objects or option recipy
     *  @return mixed object $this | bool false
     */
    public function setOptions(array $options);
    
    /**
     *  Gets an Option object
     *  @access public
     *  @param int $optionId Option array index (in order it was inserted)
     *  @return mixed object Option object | bool false
     */
    public function getOption($optionId);
    
    /**
     *  Gets several Option object. Array keys are object ID
     *  @access public
     *  @param array $optionsIds Array of option index id
     *  @return mixed array $option Associative array of wanted Options
     *  | bool false
     */
    public function getOptions(array $optionsIds);
    
    /**
     *  Gets all Option objects
     *  @access public
     *  @return mixed array $options All Optgroup options | bool false
     */
    public function getAllOptions();
    
    /**
     *  Unsets an Option object
     *  @access public
     *  @param int $optionId Option ID
     *  @param [bool $reIndex = true] Re-indexes object stack after operation
     *  @return bool
     */
    public function unsetOption($optionId, $reIndex = true);
    
    /**
     *  Unsets several Option objects
     *  @access public
     *  @param array $optionsIds Array of Option ID
     *  @return mixed object $this | bool false
     */
    public function unsetOptions(array $optionIds);
    
    /**
     *  Unsets all Option objects
     *  @access public
     *  @return object $this
     */
    public function resetOptions();
    
    /**
     *  Re-indexes object stack
     *  @access public
     */
    public function reIndex();
}
?>