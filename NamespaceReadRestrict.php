<?php
/**
 * NamespaceReadRestrict
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */
if ( !defined( 'MEDIAWIKI' ) ) {
    echo "Not a valid entry point";
    exit( 1 );
}

$wgAllowedReadNamespaces = array();
$wgHooks['TitleReadWhitelist'][] = 'NamespaceReadRestrict::namespaceWhitelist';
$wgHooks['ParserFirstCallInit'][] = 'NamespaceReadRestrict::isLoggedInParserFunction';
$wgExtensionMessagesFiles['NamespaceReadRestrictMagic']
	= dirname( __FILE__ ) . '/NamespaceReadRestrict.i18n.magic.php';

// Extension credits that show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
 'name' => 'NamespaceReadRestrict',
 'url' => 'http://www.mediawiki.org/wiki/Extension:NamespaceReadRestrict',
 'version' => '1.0.0, 2012-09-11',
 'author' => '[http://mediawiki.org/User:Leucosticte Leucosticte]',
 'description' => 'Restricts reading to specified namespaces'
);

class NamespaceReadRestrict {
	public static function namespaceWhitelist ( $title, $user, &$whitelisted ) {
		global $wgAllowedReadNamespaces;
		if ( !empty ( $wgAllowedReadNamespaces ) ) {
			if ( in_array ( $title->getNamespace(), $wgAllowedReadNamespaces ) ) {
				$whitelisted = true;
			}
		}
		if ( $whitelisted == true ) {
			return true;
		}
		return false;
	}

	public static function isLoggedInParserFunction ( &$parser ) {
		$parser->setFunctionHook ( 'isloggedin',
			'NamespaceReadRestrict::isLoggedInRenderParserFunction' );
		return true;
	}

	public static function isLoggedInRenderParserFunction ( $parser, $p1='', $p2='' ) {
		$parser->disableCache();
		global $wgUser;
		if ( $wgUser->isLoggedIn() ) {
			return 'true';
		}
		return '';
	}
}