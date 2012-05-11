<?php
/**
 * Copyright 2008, Matsimitsu (http://www.matsimitsu.nl)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @version    1.0 Beta
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * A behavior that will serialize (and unserialize) fields if they contain an array.
 *
 * @package default
 * @access public
 */
class SerializeableBehavior extends ModelBehavior{
/**
 * Unserializes the fields
 *
 * @param object $Model
 * @param array $results
 * @return array
 * @access public
 */
    function afterFind(&$Model, $results) {
        $results = $this->unserialize_items($results);
        return $results;
    }

/**
 * Unserializes the fields of an array (if the value itself was serialized)
 *
 * @param array $arr
 * @return array
 * @access public
 */
    function unserialize_items($arr){
        foreach($arr as $key => $val){
            if(is_array($val)){
                $val = $this->unserialize_items($val);
            } elseif($this->isSerialized($val)){
                $val = unserialize($val);
            }
            $arr[$key] = $val;
        }
        return $arr;
    }

/**
 * Saves all fields that do not belong to the current Model into 'with' helper model.
 *
 * @param object $Model
 * @access public
 */
    function beforeSave(&$Model) {
        $fields = $Model->data[$Model->alias];
        foreach ($fields as $key => $val) {
            if(is_array($val)){
                $val = serialize($val);
            }
            $Model->data[$Model->alias][$key] = $val;
        }
    }

/**
 * Checks if string is serialized array/object
 *
 * @param string string to check
 * @access public
 * @return boolean
 */
    function isSerialized($str) {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }
}

?>