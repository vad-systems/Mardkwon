<?php
	/* Markdown Stack classes for direct-to-HTML translateables */
	interface MarkdownStackItem {
		public function translate();
		public function get_opcode();
	}
	
	class MarkdownString implements MarkdownStackItem {
		private $string;
		public function __construct($s) {
			$this->string = $s;
		}
		
		public function get_opcode() {
			return -1;
		}
		
		public function translate() {
			$s = htmlentities($this->string, ENT_NOQUOTES);
			foreach(array(	"ul", "li", "b", "i", "em",
							"strong", "h1", "h2", "h3",
							"h4", "h5", "h6", "hr",
							"a href=\"([^\"])+\"", "a")
				as $allowed_tag) {
				$s = preg_replace("<&lt;(".$allowed_tag.")&gt;>", "<$1>", $s);
				$s = preg_replace("<&lt;/(".$allowed_tag.")&gt;>", "</$1>", $s);
			}
			return $s;
		}
	}
	
	class MarkdownOpCode implements MarkdownStackItem {
		const BOLD_ON = 0x0001;
		const BOLD_OFF = 0x0002;
		
		const ITALIC_ON = 0x0004;
		const ITALIC_OFF = 0x0005;
		
		const H1_ON = 0x0007;
		const H1_OFF = 0x0008;
		
		const H2_ON = 0x00010;
		const H2_OFF = 0x0011;
		
		const H3_ON = 0x0013;
		const H3_OFF = 0x0014;
		
		const H4_ON = 0x0016;
		const H4_OFF = 0x0017;
		
		const H5_ON = 0x0019;
		const H5_OFF = 0x0020;
		
		const H6_ON = 0x0022;
		const H6_OFF = 0x0023;
		
		const LINEBREAK = 0x0025;
		
		const PARAGRAPH_START = 0x0027;
		const PARAGRAPH_END = 0x0028;
		
		private $code;
		public function __construct($c) {
			$this->code = $c;
		}
		
		public function get_opcode() {
			return $this->code;
		}
		
		public function translate() {
			switch($this->code) {
				case self::BOLD_ON:
					return "<b>";
				case self::BOLD_OFF:
					return "</b>";
				case self::ITALIC_ON:
					return "<i>";
				case self::ITALIC_OFF:
					return "</i>";
				case self::H1_ON:
					return "<h1>";
				case self::H1_OFF:
					return "</h1>";
				case self::H2_ON:
					return "<h2>";
				case self::H2_OFF:
					return "</h2>";
				case self::H3_ON:
					return "<h3>";
				case self::H3_OFF:
					return "</h3>";
				case self::H4_ON:
					return "<h4>";
				case self::H4_OFF:
					return "</h4>";
				case self::H5_ON:
					return "<h5>";
				case self::H5_OFF:
					return "</h5>";
				case self::H6_ON:
					return "<h6>";
				case self::H6_OFF:
					return "</h6>";
				case self::LINEBREAK:
					return "<br>";
				case self::PARAGRAPH_START:
					return "<p>";
				case self::PARAGRAPH_END:
					return "</p>";
			default:
					return "&lt;".$this->code."&gt;";
			}
		}
	}
