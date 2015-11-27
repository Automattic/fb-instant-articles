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
		$attachments = get_posts( array(
			'include' => $atts['include'],
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $atts['order'],
			'orderby' => $atts['orderby']
		) );
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array(
			'post_parent' => $id,
			'exclude' => $atts['exclude'],
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $atts['order'],
			'orderby' => $atts['orderby']
		) );
	} else {
		$attachments = get_children( array(
			'post_parent' => $id,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $atts['order'],
			'orderby' => $atts['orderby']
		) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	$output = '<figure class="op-slideshow">';

	foreach ( $attachments as $attachment ) {

		$image_src = wp_get_attachment_image_src( $attachment->ID, 'large' );

		if ( $image_src ) {
			$output .= '<figure>';
			$output .= '<img src="' . esc_url( $image_src[0] ) . '" alt="' . esc_attr( get_the_title( $attachment->ID ) ) . '">';

			$caption = trim( strip_tags( $attachment->post_excerpt ) );
			//wptexturize( $attachment->post_excerpt )
			if ( trim( $attachment->post_excerpt ) ) {
				$output .= '<figcaption>' . esc_html( $caption ) . '</figcaption>';
			}
			$output .= '</figure>';
		}
	}

	$output .= '</figure>';

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

	//TODO: $set_autoplay = $atts['autoplay'] == 'true' ? ' autoplay' : '';

	if ( $audio_src ) :
		$file_url = array_reverse( explode( '/', $audio_src ) ) ;

		ob_start(); ?>

		<figure>
			<audio title="<?php echo esc_html( $file_url[0] ); ?>">
				<source src="<?php echo esc_url( $audio_src ); ?>">
			</audio>
		</figure>
			
		<?php return ob_get_clean();
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
	
		ob_start(); ?>
		<figure>
		  <video>
		    <source src="<?php echo esc_url( $video_src); ?>" type="video/<?php echo esc_html( $type); ?>" />  
		  </video>
		</figure>
			
		<?php return ob_get_clean();
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

	if ( $atts['type'] == 'video' ) :
		ob_start();  ?>
		<figure><?php foreach ($ids as $id) { ?>
			<video><source src="<?php echo wp_get_attachment_url( $id ); ?>" type="<?php $extension = wp_check_filetype( wp_get_attachment_url( $id ) ); echo 'video/'.$extension['ext']; ?>" /></video><?php } ?>
		</figure><?php
		return ob_get_clean();
	else : 
		ob_start();   ?>
		<figure><?php foreach ($ids as $id) { ?>
			<audio title="<?php echo basename( get_attached_file( $id ) );  ?>"><source src="<?php echo wp_get_attachment_url( $id ); ?>"></audio><?php } ?>
		</figure><?php
		return ob_get_clean();
	endif; 

}


