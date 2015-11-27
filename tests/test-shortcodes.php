<?php
/**
 * Test class responsible for short code output
 *
 * @since 0.1
 */
require_once('./shortcodes.php');

class Shortcodes extends WP_UnitTestCase {

	public function testShortCodeHandlerAudio()
    {
    	$input = array( 'mp3' => 'http://my.site/music.mp3');

    	$expected = '<figure><audio title="music.mp3"><source src="http://my.site/music.mp3"></audio></figure>';

    	$output = instant_articles_shortcode_handler_audio( $input );

       	$this->assertEquals( $expected, $output );
    } 

}