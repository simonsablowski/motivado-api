<?php

namespace Motivado\Api;

class Json extends \Application {
	public static function encode($value) {
		return json_encode($value);
	}
	
	public static function decode($value) {
		return json_decode($value);
	}
	
	public static function format($json) {
		$result = '';
		$p = 0;
		$indent = '  ';
		$newLine = "\n";
		$previous = '';
		$outOfQuotes = TRUE;
		
		for ($i = 0; $i <= strlen($json); $i++) {
			$character = substr($json, $i, 1);
			if ($character == '"' && $previous != '\\') {
				$outOfQuotes = !$outOfQuotes;
			} else if (($character == '}' || $character == ']') && $outOfQuotes) {
				$result .= $newLine;
				$p--;
				for ($j = 0; $j < $p; $j++) {
					$result .= $indent;
				}
			}
			$result .= $character;
			if (($character == ',' || $character == '{' || $character == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($character == '{' || $character == '[') $p++;
				for ($j = 0; $j < $p; $j++) {
					$result .= $indent;
				}
			}
			$previous = $character;
		}
		return $result;
	}
}