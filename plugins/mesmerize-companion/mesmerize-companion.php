<?php
/*
 *	Plugin Name: Mesmerize Companion
 *  Author: Extend Themes
 *  Description: The Mesmerize Companion plugin adds drag and drop page builder functionality to the Mesmerize theme.
 *
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Version: 1.6.158
 * Text Domain: mesmerize-companion
 */

// Make sure that the companion is not already active from another theme

if ( defined( 'EXTENDTHEMES_NO_COMPANION' ) && EXTENDTHEMES_NO_COMPANION ) {
	return;
}

if ( ! defined( 'MESMERIZE_COMPANION_PHP_VERSION' ) ) {
	define( 'MESMERIZE_COMPANION_PHP_VERSION', '5.4' );
}

if ( ! defined( 'MESMERIZE_COMPANION_VERSION' ) ) {
	define( 'MESMERIZE_COMPANION_VERSION', '1.6.158' );
}

function mesmerize_companion_php_version_notice() {     ?>
	<div class="notice notice-alt notice-error notice-large">
		<h4><?php _e( 'Mesmerize Companion can not run!', 'mesmerize-companion' ); ?></h4>
		<p>
			<?php _e( 'You need to update your PHP version to use the <strong>Mesmerize Companion</strong>.', 'mesmerize-companion' ); ?> <br />
			<?php _e( 'Current php version is:', 'mesmerize-companion' ); ?> <strong>
				<?php echo phpversion(); ?></strong>, <?php _e( 'and the minimum required version is ', 'mesmerize-companion' ); ?>
			<strong><?php echo MESMERIZE_COMPANION_PHP_VERSION; ?></strong>
		</p>
	</div>
	<?php
}

if ( version_compare( phpversion(), MESMERIZE_COMPANION_PHP_VERSION, '<' ) ) {
	add_action( 'admin_notices', 'mesmerize_companion_php_version_notice' );

	return;
}


if ( ! defined( 'MESMERIZE_COMPANION_AUTOLOAD' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	define( 'MESMERIZE_COMPANION_AUTOLOAD', true );
}


require_once __DIR__ . '/support/wp-5.8.php';
Mesmerize\Companion::load( __FILE__ );
add_filter( 'mesmerize_is_companion_installed', '__return_true' );

add_action( 'init', 'mesmerize_companion_load_text_domain' );

function mesmerize_companion_load_text_domain() {
	load_plugin_textdomain( 'mesmerize-companion', false, basename( dirname( __FILE__ ) ) . '/languages' );
}


function mesmerize_get_edit_in_mesmerize_label() {
	$template   = get_template();
	$stylesheet = get_stylesheet();

	$theme = $template;

	if ( in_array( $stylesheet, array( 'highlight', 'empowerwp' ) ) ) {
		$theme = $stylesheet;
	}

	$theme_name = str_replace( ' PRO', '', wp_get_theme( $theme )->get( 'Name' ) );

	return sprintf( __( 'Edit with %s', 'mesmerize-companion' ), $theme_name );
}
