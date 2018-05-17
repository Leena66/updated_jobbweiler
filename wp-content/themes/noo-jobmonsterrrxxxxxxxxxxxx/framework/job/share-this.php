<?php
/**
 * Share This
 * Awards points to users who share a post.
 * @see http://mycred.me/support/tutorials/awarding-points-for-users-who-share-a-post/
 * @version 1.0
 */
if ( function_exists( 'mycred_render_shortcode_link' ) ) {
	add_shortcode( 'mycred_share_this', 'mycred_render_shortcode_share_this' );
	function mycred_render_shortcode_share_this( $attr, $link_title )
	{
		// Get URL (we assume you only use this shortcode inside the loop)
		$url = get_permalink();

		// Facebook
		if ( $attr['href'] == 'facebook' )
			$attr['href'] = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url );

		// Twitter
		elseif ( $attr['href'] == 'twitter' )
			$attr['href'] = 'http://twitter.com/home?status=' . urlencode( $url );

		// Google
		elseif ( $attr['href'] == 'google' )
			$attr['href'] = 'http://plus.google.com/share?url=' . urlencode( $url );

		// Pinterest
		elseif ( $attr['href'] == 'pinterest' )
			$attr['href'] = 'http://pinterest.com/pin/create/button/?url=' . urlencode( $url );

		// Always make links open in a new window
		$attr['target'] = '_blank';

		// Pass it on
		return mycred_render_shortcode_link( $attr, $link_title );
	}
}
?>