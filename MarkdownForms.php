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
		<input type="{md_type}" name="{md_name}" value="{md_value}" placeholder="{md_placeholder}" {md_attribs}>
	</div>
	';
	
	private $sTextareaGroupTemplate = '
	<div class="form-group">
		<label for="{md_name}">{md_label}</label>
		<textarea name="{md_name}" rows="{md_rows}" cols="{md_cols}" {md_attribs}>
			{md_value}
		</textarea>
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
		# First, handle inline inputs:  ?[type]("label" "value" "placeholder" rows*cols){#id .class}
		# Don't forget: encode * and _
		#
		$text = preg_replace_callback('{
			(				# wrap whole match in $1
			  ?\[
				('.$this->nested_brackets_re.')		# type = $2
			  \]
			  \s?			# One optional whitespace character
			  \(			# literal paren
				[ \n]*
				(			# $3
				  ([\'"])	# quote char = $4
				  (.*?)		# label = $5
				  \4		# matching quote
				  [ \n]*
				)			
				[ \n]*
				(			# $6
				  ([\'"])	# quote char = $7
				  (.*?)		# value = $8
				  \7		# matching quote
				  [ \n]*
				)			
				[ \n]*
				(			# $9
				  ([\'"])	# quote char = $10
				  (.*?)		# placeholder = $11
				  \10		# matching quote
				  [ \n]*
				)?			# placeholder is optional
				[ \n]*
				(			# $12
				([0-9]+)	# rows = $13
				[ \n]*
				\*
				[ \n]*
				([0-9]+)	# cols = $14
				[ \n]*
				)?			# rows*cols is optional
			  \)
			  (?:[ ]? '.$this->id_class_attr_catch_re.' )?	 # $15 = id/class attributes
			)
			}xs',
			array($this, '_doInputs_callback'), $text);

		return $text;
	}
	
	protected function _doInputs_callback($matches) {
		$whole_match = $matches[1];
		$type = $this->encodeAttribute($matches[2]);
		$label = $this->encodeAttribute($matches[5]);
		$value = $this->encodeAttribute($matches[8]);
		$placeholder = $this->encodeAttribute($matches[11]);
		
		if($type != "textarea"){
			$attr = $this->doExtraAttributes("input", $dummy =& $matches[15]);
		}else{
			$attr = $this->doExtraAttributes("textarea", $dummy =& $matches[15]);
			$rows = $this->encodeAttribute($matches[13]);
			$cols = $this->encodeAttribute($matches[14]);
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
