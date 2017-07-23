<?php

add_filter( 'upload_mimes', function ( $mime_types ) {
	$mime_types['sgf'] = 'text/sgf';
	return $mime_types;
} );

add_filter( 'post_mime_types', function ( $post_mime_types ) {
	$post_mime_types['text/sgf'] = array(
		__( 'Kifus', 'igo-lang' ),
		__( 'Manage Kifus', 'igo-lang' ),
		_n_noop( 'Kifu <span class="count">(%s)</span>', 'Kifus <span class="count">(%s)</span>' )
	);
	return $post_mime_types;
} );