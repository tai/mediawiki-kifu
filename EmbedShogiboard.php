<?php
/*
 * EmbedChessboard extension for MediaWiki:
 * shows chess games in a javascript chessboard on MediaWiki articles.
 *
 * @package MediaWiki
 * @subpackage Extensions
 * @author Taisuke Yamada
 * @copyright (C) Taisuke Yamada
 *
 * Usage
 *
 *   <kifu parameter=value ...> 
 *   ... gameplay in KIFU format ...
 *   </kifu>
 */

if (!defined('MEDIAWIKI')) die();

$wgExtensionFunctions[] = "wfEmbedShogiboard";
$wgExtensionCredits['parserhook'][] = array(
    'name'        => 'EmbedShogiboard',
    'version'     => '0.01',
    'author'      => 'Taisuke Yamada / @tyamadajp',
    'url'         => 'http://twilog.org/tyamadajp',
    'description' => 'Tag for embedding Shogi game record in MediaWiki page'
);
 
function wfEmbedShogiboard() {
    global $wgParser;
    $wgParser->setHook( "kifu", "renderEmbedShogiboard" );
    $wgParser->setHook( "csa",  "renderEmbedShogiboard" );
    $wgParser->setHook( "usi",  "renderEmbedShogiboard" );
}
 
function renderEmbedShogiboard( $input, $args, $parser ) {
    global $wgScriptPath;
    
    $hash = md5($input);
    $base = $wgScriptPath . "/extensions/EmbedShogiboard";

    $parser->disableCache();

    // Pass board data to javascript using MD5 as element id.
    $output  = "";
    $output .= "<textarea style='display: none' cols='40' rows='8'";
    $output .= " id='" . $hash . "'>";
    $output .= $input;
    $output .= "</textarea>\n";

    // Embed board in iframe
    $output .= "<iframe src='" . $base . "/board.html?";
    $output .= "&amp;id=" . $hash;
    $output .= "&amp;im=" . rawurlencode($args['initialmove']);
    $output .= "&amp;ap=" . rawurlencode($args['autoplay']);
    $output .= "'";
    $output .= " width='100%' height='500' frameborder='0'";
    $output .= " scrolling='no' marginheight='0' marginwidth='0'>";
    $output .= "Need iframe support to show the board!</iframe>";
    
    return $output;
}
