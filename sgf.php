<?php

// ---------------------------------------------------------------------------------------------------------------------
// Render the shortcode
//
// Available parameters:
//   width
//   maxwidth
//   stones
//   background
//   limit="top,right,bottom,left"
//   static
//   float
// ---------------------------------------------------------------------------------------------------------------------

add_shortcode( 'wgo', 'igo_shortcode_sgf' );
function igo_shortcode_sgf( $atts, $content = null ) {
	extract(
		shortcode_atts(
			array(
				'width' => get_option( 'igo_default_width' ),
				'maxwidth' => get_option( 'igo_max_width' ),
				'stones' => get_option( 'igo_stone_handler' ),
				'background' => get_option( 'igo_background' ),
				'move' => null,
				'static' => null,
				'limit' => null,
				'float' => null,
			),
			$atts
		)
	);

	if ( !( strpos( $background, '#' ) === 0 ) ) {
		$background = plugins_url( 'img/' . $background, __FILE__ );
	}

	$out = "<div data-wgo-board='stoneHandler: WGo.Board.drawHandlers."
		. strtoupper( $stones ) . ", background: \"" . $background . "\"";

	if ( $limit != null ) {
		$a = preg_split( "/,/", $limit );
		$out .= ", section: {top: " . $a[0] . ", right: " . $a[1] . ", bottom: " . $a[2] . ", left: " . $a[3] . "}";
	}
	$out .= "'";

	if ( $move != null ) {
		$out .= " data-wgo-move='" . $move . "'";
	}

	$class = "";
	$out .= " style='width: " . $width . "; max-width: " . $maxwidth;
	if ( $float != null ) {
		if ( $float == "left" ) {
			$out .= "; float: left";
			$class = "class='wgo-player-floating-left'";
		}
		if ( $float == "right" ) {
			$out .= "; float: right";
			$class = "class='wgo-player-floating-right'";
		}
	}
	$out .= "' " . $class;

	if ( $static != null ) {
		$out .= " data-wgo-diagram='";
	} else {
		$out .= " data-wgo='";
	}
	$content = preg_replace( '@<br[^>]*/?>@', '', $content );
	$out .= str_replace( array( "\r", "\r\n", "\n" ), '', $content ) . "'></div>";
	return $out;
}
