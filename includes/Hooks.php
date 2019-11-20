<?php

namespace DokitParsingFunctions;

use Parser;

class Hooks {

	/**
	 * watch Hook:ParserFirstCallInit
	 */
	public static function onParserFirstCallInit(Parser $parser) {

		// Create a function hook associating the "example" magic word with renderExample()
		$parser->setFunctionHook ( 'ifListContains', [
				self::class,
				'renderIfListContains'
		] );
	}

	// Render the output of {{#ifContain:}}.
	public static function renderIfListContains( $parser, $param1 = '', $param2 = '', $param3 = '', $param4 = '') {
		$list = explode(',', $param1);
		$value = trim($param2);

		foreach ($list as $item) {
			if (trim($item) == $value) {
				return $param3;
			}
			// we must match 'Utilisateurs autoconfirmés' or 'Utilisateurs connectés'
			// in string like :
			//$item = '[[Demo Dokit:Utilisateurs autoconfirmés|Utilisateurs connectés]';
			//$item = '[[Demo Dokit:Utilisateurs autoconfirmés]';
			if ( preg_match( '/^\[\[([^:]+:)([^\|]+)(\|([^\]]+))?\]/', $item, $matches)) {
				if (trim($matches[2]) == $value) {
					return $param3;
				}
				if (isset($matches[4]) && trim($matches[4]) == $value) {
					return $param3;
				}
			}
			if ( preg_match( '/^\[\[([^:]+:)([^\|\]]+)/', $item, $matches)) {
				if (trim($matches[2]) == $value) {
					return $param3;
				}
			}
		}
		return $param4;
	}
}
