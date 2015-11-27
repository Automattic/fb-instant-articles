<?php

add_shortcode( 'gallery', 'instant_articles_shortcode_handler_gallery' );

/**
 * Gallery Shortcode. Based on the built in gallery shortcode
 * @param  array     $attr    Array of attributes passed to shortcode.
 * @return string             The generated content.
*/
function instant_articles_shortcode_handler_gallery( $attr ) {

	$post = get_post();

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$atts = shortcode_atts( array(
		'id'         => $post ? $post->ID : 0,
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'include'    => '',
		'exclude'    => '',
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$include = explode( ',', $atts['include'] );

		$attachments = new WP_Query( array(
			'post__in' => $include,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $atts['order'],
			'orderby' => $atts['orderby']
		) );
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$exclude = explode( ',', $atts['exclude'] );

		$attachments = new WP_Query( array(
			'post_parent' => $id,
			'post__not_in' => $exclude,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $atts['order'],
			'orderby' => $atts['orderby']
		) );
	} else {
		$attachments = new WP_Query( array(
			'post_parent' => $id,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $atts['order'],
			'orderby' => $atts['orderby']
		) );
	}

	$output = '';

	if ( $attachments->have_posts() ) {
		$output = '<figure class="op-slideshow">';

		while ( $attachments->have_posts() ) {
			$attachments->the_post();

			$image_src = wp_get_attachment_image_src( get_the_ID(), 'large' );

			if ( $image_src ) {
				$output .= '<figure>';
				$output .= '<img src="' . esc_url( $image_src[0] ) . '" alt="' . esc_attr( get_the_title() ) . '">';

				$caption = trim( strip_tags( $attachments->post->post_excerpt ) );
				if ( $caption ) {
					$output .= '<figcaption>' . esc_html( $caption ) . '</figcaption>';
				}
				$output .= '</figure>';
			}
		}
		
		$output .= '</figure>';
	}
	
	wp_reset_postdata();

	return $output;
}


add_shortcode( 'caption', 'instant_articles_shortcode_handler_caption' );
add_shortcode( 'wp_caption', 'instant_articles_shortcode_handler_caption' );

/**
 * Caption/WP-Caption Shortcode. Based on the built in caption shortcode
 * @param  array     $attr      Array of attributes passed to shortcode.
 * @param  string    $content   The content passed to the shortcode.
 * @return string    $output    The generated content.
*/
function instant_articles_shortcode_handler_caption( $attr, $content = null ) {
	// New-style shortcode with the caption inside the shortcode with the link and image tags.
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}

	$atts = shortcode_atts( array(
		'figcaptionclass'  => '',
		'caption'          => '',
		'cite'             => '',
		'subtitle'         => '',
	), $attr, 'caption' );

	if ( ! strlen( trim( $atts['caption'] ) ) ) {
		return '';
	}

	$output = '';

	$content = do_shortcode( $content );

	$doc = new DOMDocument();
	$doc->loadHTML( '<html><body>' . $content . '</body></html>' );
	$imgs = $doc->getElementsByTagName( 'img' );

	if ( $imgs->length > 0 ) {
		$img_src =  $imgs->item(0)->getAttribute( 'src' );
		if ( $img_src ) {

			$alt = $imgs->item(0)->getAttribute( 'alt' );

			$classes = array();
			$classes = trim( $atts['figcaptionclass'] );
			$class_attr = ( strlen( $classes ) ) ? ' class="' . esc_attr( $classes ) . '"' : '';

			$caption = trim( strip_tags( $atts['caption'] ) );

			$subtitle = ( strlen( $atts['subtitle'] ) ) ? '<h2>' . esc_html( $atts['subtitle'] ) . '</h2>' : '';			
			$cite = ( strlen( $atts['cite'] ) ) ? '<cite>' . esc_html( $atts['cite'] ) . '</cite>' : '';

			$output = '<figure><img src="' . esc_url( $img_src ) . '" alt="' . esc_attr( $alt ) . '"><figcaption' . $class_attr . '><h1>' . esc_html( $caption ) . '</h1>' . $subtitle . $cite . '</figcaption></figure>';
		}
	}

	return $output;
}


add_shortcode( 'audio', 'instant_articles_shortcode_handler_audio' );

/**
 * Audio Shortcode
 * @param  array     $atts       Array of attributes passed to shortcode.
 * @return string                The generated content.
*/
function instant_articles_shortcode_handler_audio( $atts ) {	

	if ( $atts['mp3'] ) {
		$audio_src =  $atts['mp3'];
	} else if ( $atts['src'] ) {
		$audio_src =  $atts['src'];
	} else if ( $atts['ogg'] ) {
		$audio_src =  $atts['ogg'];
	} else if ( $atts['wav'] ) {
		$audio_src =  $atts['wav'];
	} else {
		$audio_src = null;
	}

	if ( $audio_src ) :
		$file_url = array_reverse( explode( '/', $audio_src ) ) ;

		return	'<figure><audio title="' . esc_html( $file_url[0] ) . '"><source src="' . esc_url( $audio_src ) .'"></audio></figure>';
					
	endif;

}

add_shortcode( 'video', 'instant_articles_shortcode_handler_video' );

/**
 * Video Shortcode
 * @param  array     $atts       Array of attributes passed to shortcode.
 * @return string                The generated content.
*/
function instant_articles_shortcode_handler_video( $atts ) {	

	if ( $atts['mp4'] ) {
		$video_src =  $atts['mp4'];
		$type = 'mp4';
	} else if ( $atts['src'] ) {
		$video_src =  $atts['src'];
		$file_src_array = array_reverse( explode( '.', $video_src ) ) ;
		$type = $file_src_array[0];
	} else if ( $atts['ogv'] ) {
		$video_src =  $atts['ogv'];
		$type = 'ogv';
	} else if ( $atts['webm'] ) {
		$video_src =  $atts['webm'];
		$type = 'webm';
	} else {
		$video_src = null;
	}

	if ( $video_src ) :
	
		return '<figure><video><source src="' . esc_url( $video_src ) . '" type="video/' . esc_html( $type ) . '" /></video></figure>';	
	
	endif;
}

add_shortcode( 'playlist', 'instant_articles_shortcode_handler_playlist' );
/**
 * Playlist Shortcode
 * @param  array     $atts       Array of attributes passed to shortcode.
 * @return string                The generated content.
*/
function instant_articles_shortcode_handler_playlist( $atts ) {	

	$ids = explode( ',', $atts['ids'] );

	$output = '<figure>';
	
	if ( 'video' === $atts['type'] ) :
		foreach ($ids as $id) {
			$extension = wp_check_filetype( wp_get_attachment_url( $id ) ); 
			$output .= '<video><source src="' . wp_get_attachment_url( $id ) . '" type="video/' . $extension['ext'] .'" /></video>';
		}
	else : 
		foreach ($ids as $id) { 
			$output .= '<audio title="' . basename( get_attached_file( $id ) ) . '"><source src="' . wp_get_attachment_url( $id ) . '"></audio>';
		}
	endif; 
	
	$output .= '</figure>';
	
	return $output;
}


