<?php
/*
Author: http://keithdevens.com/software/phpxml
*/

class btc_XML{
	var $parser;
	var $document;
	var $parent;
	var $stack;
	var $last_opened_tag;

	function btc_XML() {
 		$this->parser = &xml_parser_create('UTF-8');
		xml_parser_set_option(&$this->parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object(&$this->parser, &$this);
		xml_set_element_handler(&$this->parser, 'open','close');
		xml_set_character_data_handler(&$this->parser, 'data');
	}
	
	function destruct() { 
		xml_parser_free(&$this->parser); 
	}
	
	function &parse(&$data) {
		$this->document = array();
		$this->stack	= array();
		$this->parent   = &$this->document;
		return xml_parse(&$this->parser, &$data, true) ? $this->document : NULL;
	}
	
	function open(&$parser, $tag, $attributes){
		$this->data = ''; 
		$this->last_opened_tag = $tag;
		if (is_array($this->parent) && array_key_exists($tag,$this->parent)) {
			if (is_array($this->parent[$tag]) && array_key_exists(0,$this->parent[$tag])) { 
				$key = btc_count_numeric_items($this->parent[$tag]);
			} else {
				if (array_key_exists("$tag attr",$this->parent)) {
					$arr = array('0 attr'=>&$this->parent["$tag attr"], &$this->parent[$tag]);
					unset($this->parent["$tag attr"]);
				} else {
					$arr = array(&$this->parent[$tag]);
				}
				$this->parent[$tag] = &$arr;
				$key = 1;
			}
			$this->parent = &$this->parent[$tag];
		} else {
			$key = $tag;
		}
		
		if ($attributes) {
			$this->parent["$key attr"] = $attributes;
		}
		
		$this->parent  = &$this->parent[$key];
		$this->stack[] = &$this->parent;
	}
	
	function data(&$parser, $data){
		if ($this->last_opened_tag != NULL) {
			$this->data .= $data;
		}
	}
	
	function close(&$parser, $tag){
		if ($this->last_opened_tag == $tag) {
			$this->parent = $this->data;
			$this->last_opened_tag = NULL;
		}
		
		array_pop($this->stack);
		
		if ($this->stack) {
			$this->parent = &$this->stack[count($this->stack)-1];
		}
	}
}

function btc_count_numeric_items(&$array){
	return is_array($array) ? count(array_filter(array_keys($array), 'is_numeric')) : 0;
}
?>