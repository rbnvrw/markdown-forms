<?php
/**
* @package Markdown
* @subpackage Markdown Forms
* @version 1.0
* @author Ruben Verweij <development@rubenverweij.nl>
* @link https://github.com/rbnvrw/markdown-forms
* @license http://opensource.org/licenses/MIT
*
*/

namespace RubenVerweij;

class MarkdownForms extends \Michelf\MarkdownExtra {

	private $sInputGroupTemplate = '
	<div class="form-group">
		<label for="{md_name}">{md_label}</label>
		<input type="{md_type}" id="{md_name}" name="{md_name}" value="{md_value}" placeholder="{md_placeholder}" {md_attribs}>
	</div>
	';
	
	private $sTextareaGroupTemplate = '
	<div class="form-group">
		<label for="{md_name}">{md_label}</label>
		<textarea name="{md_name}" id="{md_name}" rows="{md_rows}" cols="{md_cols}" placeholder="{md_placeholder}" {md_attribs}>{md_value}</textarea>
	</div>
	';
		
	public function __construct($sInputGroupTemplate = '', $sTextareaGroupTemplate = '') {
	#
	# Constructor function. Initialize the parser object.
	#
		# Insert extra document, block, and span transformations. 
		# Parent constructor will do the sorting.
		$this->span_gamut += array(
			"doInputs"        => 70
		);
		
		if(!empty($sInputGroupTemplate)){
			$this->sInputGroupTemplate = $sInputGroupTemplate;
		}
		
		if(!empty($sTextareaGroupTemplate)){
			$this->sTextareaGroupTemplate = $sTextareaGroupTemplate;
		}
		
		parent::__construct();
	}

	protected function doInputs($text) {
	#
	# Turn Markdown input shortcuts into <input> tags.
	#
		#
		# First, handle inline inputs:  ?{type}("label" "value" "placeholder" rows*cols){.class}
		# Don't forget: encode * and _
		#
		
		/*
		\?\{
		\s*(\w+)\s*                     # $1 = type
		\}
		
		\(\s*(([\'\"])(.*?)\3)?         # $4 = label
		\s*(([\'\"])(.*?)\6)?           # $7 = value
		\s*(([\'\"])(.*?)\9)?           # $10 = placeholder
		\s*((\d+)\*(\d+))?              # $12 = rows, $13 = cols
		\s*\)
		
		(\{(.*?)\})?                    # $15 = extra attributes
		*/
		$text = preg_replace_callback('
		/
		\?\{
		\s*(\w+)\s*
		\}
		
		\(\s*(([\'\"])(.*?)\3)?
		\s*(([\'\"])(.*?)\6)?
		\s*(([\'\"])(.*?)\9)?
		\s*((\d+)\*(\d+))?
		\s*\)
		
		(\{(.*?)\})?
		/xs', array($this, '_doInputs_callback'), $text);

		return $text;
	}
	
	protected function _doInputs_callback($matches) {
		$whole_match = $matches[0];
		$type = $this->encodeAttribute(trim($matches[1]));
		$label = $this->encodeAttribute(trim($matches[4]));
		$value = $this->encodeAttribute(trim($matches[7]));
		$placeholder = $this->encodeAttribute(trim($matches[10]));
		
		if($type != "textarea"){
			$attr = $this->doExtraAttributes("input", $dummy =& $matches[15]);
		}else{
			$attr = $this->doExtraAttributes("textarea", $dummy =& $matches[15]);
			$rows = $this->encodeAttribute(trim($matches[12]));
			$cols = $this->encodeAttribute(trim($matches[13]));
		}
		
		if($type != "textarea"){	
			$result = $this->sInputGroupTemplate;
		}else{
			$result = $this->sTextareaGroupTemplate;
			$result = str_replace('{md_rows}', $rows, $result);
			$result = str_replace('{md_cols}', $cols, $result);
		}
		
		$result = str_replace('{md_value}', $value, $result);
		$result = str_replace('{md_name}', $this->sanitize_key($label), $result);
		$result = str_replace('{md_type}', $type, $result);
		$result = str_replace('{md_label}', $label, $result);
		$result = str_replace('{md_placeholder}', $placeholder, $result);
		$result = str_replace('{md_attribs}', $attr, $result);

		return $this->hashPart($result);
	}
	
	/**
	 * Sanitizes a string key.
	 *
	 * Lowercase alphanumeric characters, dashes and underscores are allowed.
	 *
	 * @param string $key String key
	 * @return string Sanitized key
	 */
	function sanitize_key( $key ) {
		$raw_key = $key;
		$key = strtolower( $key );
		$key = preg_replace( '/[^a-z0-9_\-]/', '', $key );

		return $key;
	}

}
