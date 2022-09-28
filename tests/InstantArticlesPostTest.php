<?php
/**
 * Facebook Instant Articles for WP.
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 *
 * @package default
 */

declare(strict_types=1);

require_once( './class-instant-articles-post.php' );

/**
 * Test class responsible for constructing article content
 *
 * @since 0.1
 */
class InstantArticlesPostTest extends WP_UnitTestCase {

	protected $post_id;
	private $instant_articles_post;

	public function set_up(): void {
		$user_id = self::factory()->user->create();
		$post    = self::factory()->post->create_and_get(
			array(
				'post_author'  => $user_id,
				'post_title'   => 'Article title',
				'post_content' => 'something',
				'post_excerpt' => 'This is the excerpt.',
				'post_date'    => '',
				'post_modified' => '',
			)
		);
		$this->post_id               = $post->ID;
		$this->instant_articles_post = new Instant_Articles_Post( $post );
	}

	public function test_can_create_instance(): void {
		self::assertInstanceOf( 'Instant_Articles_Post', $this->instant_articles_post );
	}

	public function test_can_get_post_fields(): void {
		self::assertSame( 'Article title', $this->instant_articles_post->get_the_title() );
		self::assertSame( 'Article title', $this->instant_articles_post->get_the_title_rss() );
		self::assertSame( 'http://' . WP_TESTS_DOMAIN . '/?p=' . $this->post_id,  $this->instant_articles_post->get_canonical_url() );
		self::assertIsString( $this->instant_articles_post->get_the_excerpt(), 'Expected string assertion failed.' );
		self::assertIsString( $this->instant_articles_post->get_the_excerpt_rss(), 'Expected string assertion failed.' );
	}

	public function test_featured_image_is_array(): void {
		self::assertIsArray( $this->instant_articles_post->get_the_featured_image() );
	}

	public function test_kicker_is_empty_for_no_category(): void {
		self::assertEmpty( $this->instant_articles_post->get_the_kicker() );
	}
}
