<?php
/**
 * Plugin Name: WPCandy Dashboard Feed
 * Plugin URI: http://wpcandy.com/made/wpcandy-dashboard-feed-plugin/
 * Description: This plugin adds a widget to your WordPress Dashboard that pulls in the most recent posts from WPCandy.com. The number of posts displayed, and the category of posts displayed, can both be configured.
 * Version: 0.3.4
 * Author: WPCandy
 * Author URI: http://wpcandy.com/
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
**/


// Creates the custom dashboard feed RSS box
function wpcandy_dashboard_custom_feed_output() {
	
	$widget_options = wpcandy_dashboard_options();
	
	// Variable for RSS feed
	$wpcandy_feed = 'http://feeds.feedburner.com/wpcandy';
		
	if ( $widget_options['posts_feed'] == 'All posts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandy'; } else {  };
	if ( $widget_options['posts_feed'] == 'News posts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandynews'; } else {  };
	if ( $widget_options['posts_feed'] == 'Opinion posts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandyeditorials'; } else {  };
	if ( $widget_options['posts_feed'] == 'Tutorial posts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandytutorials'; } else {  };
	if ( $widget_options['posts_feed'] == 'Feature posts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandyfeatures'; } else {  };
	if ( $widget_options['posts_feed'] == 'Podcasts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandypodcast'; } else {  };
	if ( $widget_options['posts_feed'] == 'Interview posts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandyinterviews';  } else {  };
	if ( $widget_options['posts_feed'] == 'Review posts' ) { $wpcandy_feed = 'http://feeds.feedburner.com/wpcandyreviews'; } else {  };
	
	echo '<div class="rss-widget" id="wpcandy-rss-widget">';
		wp_widget_rss_output(array(
			'url' => $wpcandy_feed,
			'title' => 'Latest Posts from WPCandy',
			'items' => $widget_options['posts_number'],
			'show_summary' => 0,
			'show_author' => 0,
			'show_date' => 0
		));
	echo "</div>";
}


// Function used in the action hook
function wpcandy_add_dashboard_widgets() {
	
	wp_add_dashboard_widget('wpcandy_dashboard_custom_feed', 'Latest Posts from WPCandy', 'wpcandy_dashboard_custom_feed_output', 'wpcandy_dashboard_setup' );

}


function wpcandy_dashboard_options() {
	
	$defaults = array( 'posts_number' => 5, 'posts_feed' => 8);
	if ( ( !$options = get_option( 'wpcandy_dashboard_custom_feed' ) ) || !is_array($options) )
		$options = array();
	return array_merge( $defaults, $options );

}


function wpcandy_dashboard_setup() {
 
	$options = wpcandy_dashboard_options();
 
	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'wpcandy_dashboard_custom_feed' == $_POST['widget_id'] ) {
		foreach ( array( 'posts_number', 'posts_feed' ) as $key )
				$options[$key] = $_POST[$key];
		update_option( 'wpcandy_dashboard_custom_feed', $options );
	}
 
?>
 
		<p>
			<label for="posts_number"><?php _e('How many items?', 'wpcandy_dashboard_custom_feed' ); ?>
				<select id="posts_number" name="posts_number">
					<?php for ( $i = 5; $i <= 20; $i = $i + 1 )
						echo "<option value='$i'" . ( $options['posts_number'] == $i ? " selected='selected'" : '' ) . ">$i</option>";
						?>
					</select>
				</label>
 		</p>

		<p>
			<label for="posts_feed"><?php _e('Which category should display?', 'wpcandy_dashboard_custom_feed' ); ?>
				<select id="posts_feed" name="posts_feed">
					<option value="All posts" <?php if ( $options['posts_feed'] == 'All posts' ) { ?>selected="select"<?php } ?>>All posts</option>
					<option value="News posts" <?php if ( $options['posts_feed'] == 'News posts' ) { ?>selected="select"<?php } ?>>News posts</option>
					<option value="Opinion posts" <?php if ( $options['posts_feed'] == 'Opinion posts' ) { ?>selected="select"<?php } ?>>Opinion posts</option>
					<option value="Tutorial posts" <?php if ( $options['posts_feed'] == 'Tutorial posts' ) { ?>selected="select"<?php } ?>>Tutorial posts</option>
					<option value="Feature posts" <?php if ( $options['posts_feed'] == 'Feature posts' ) { ?>selected="select"<?php } ?>>Feature posts</option>
					<option value="Podcasts" <?php if ( $options['posts_feed'] == 'Podcasts' ) { ?>selected="select"<?php } ?>>Podcasts</option>
					<option value="Interview posts" <?php if ( $options['posts_feed'] == 'Interview posts' ) { ?>selected="select"<?php } ?>>Interview posts</option>
					<option value="Review posts" <?php if ( $options['posts_feed'] == 'Review posts' ) { ?>selected="select"<?php } ?>>Review posts</option>
				</select>	
			</label>
 		</p>

 
<?php
 }


// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'wpcandy_add_dashboard_widgets' );


// Adds stylesheet for mint background image
add_action( 'admin_print_styles', 'wpcandy_load_custom_admin_css' );


// The load CSS function
function wpcandy_load_custom_admin_css() {
	wp_enqueue_style( 'wpcandy_custom_admin_css', plugins_url( '/style.css', __FILE__ ) );
}

?>