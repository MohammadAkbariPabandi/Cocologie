<?php
/**
 * WPForms_Pro class file.
 */

// phpcs:disable Generic.Commenting.DocComment.MissingShort
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection AutoloadingIssuesInspection */
// phpcs:enable Generic.Commenting.DocComment.MissingShort

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPForms\Admin\Builder\TemplateSingleCache;
use WPForms\Admin\Builder\TemplatesCache;
use WPForms\Db\Payments\Meta as PaymentsMeta;
use WPForms\Db\Payments\Payment;
use WPForms\Pro\Db\Files\ProtectedFiles;
use WPForms\Pro\Db\Files\Restrictions;
use WPForms\Helpers\DB;
use WPForms\Integrations\UsageTracking\UsageTracking;
use WPForms\Logger\Repository;
use WPForms\Pro\Integrations\LiteConnect\Integration;
use WPForms\Tasks\Meta as TasksMeta;
use WPForms\Admin\Notice;

/**
 * WPForms Pro. Load Pro specific features/functionality.
 *
 * @since 1.2.1
 */
class WPForms_Pro {

	/**
	 * Custom tables and their handlers.
	 *
	 * @since 1.9.0
	 */
	public const CUSTOM_TABLES = [
		'wpforms_entries'           => WPForms_Entry_Handler::class,
		'wpforms_entry_fields'      => WPForms_Entry_Fields_Handler::class,
		'wpforms_entry_meta'        => WPForms_Entry_Meta_Handler::class,
		'wpforms_logs'              => Repository::class,
		'wpforms_payment_meta'      => PaymentsMeta::class,
		'wpforms_payments'          => Payment::class,
		'wpforms_protected_files'   => ProtectedFiles::class,
		'wpforms_file_restrictions' => Restrictions::class,
		'wpforms_tasks_meta'        => TasksMeta::class,
	];

	/**
	 * Primary class constructor.
	 *
	 * @since 1.2.1
	 */
	public function __construct() {

		$this->constants();
		$this->includes();

		$this->init();
	}

	/**
	 * Setup plugin constants.
	 *
	 * @since 1.2.1
	 */
	public function constants() {

		// Plugin Updater API.
		if ( ! defined( 'WPFORMS_UPDATER_API' ) ) {
			/**
			 * Define the WPForms Updater API URL.
			 *
			 * @since 1.0.0
			 */
			define( 'WPFORMS_UPDATER_API', 'https://wpformsapi.com/license/v1' );
		}
	}

	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 */
	private function includes() {

		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/class-entry.php';
		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/class-entry-fields.php';
		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/class-entry-meta.php';
		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/class-conditional-logic-core.php';
		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/class-conditional-logic-fields.php';
		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/payments/class-payment.php';

		if ( is_admin() || wp_doing_cron() || wpforms_doing_wp_cli() ) {
			require_once WPFORMS_PLUGIN_DIR . 'pro/includes/admin/ajax-actions.php';
			require_once WPFORMS_PLUGIN_DIR . 'pro/includes/admin/entries/class-entries-single.php';
			require_once WPFORMS_PLUGIN_DIR . 'pro/includes/admin/class-updater.php';
			require_once WPFORMS_PLUGIN_DIR . 'pro/includes/admin/class-license.php';
		}
	}

	/**
	 * Hook in various places in WordPress and WPForms.
	 *
	 * @since 1.5.9
	 */
	public function init() {

		$this->hooks();
		$this->allow_wp_auto_update_plugins();
	}

	/**
	 * Hook into WordPress lifecycle.
	 *
	 * @since 1.7.5
	 */
	private function hooks() {

		add_filter( 'plugin_action_links_' . plugin_basename( WPFORMS_PLUGIN_DIR . 'wpforms.php' ), [ $this, 'plugin_action_links' ], 11, 4 );
		add_filter( 'plugin_action_links_wpforms-lite/wpforms.php', [ $this, 'replace_action_links' ] );
		add_filter( 'install_plugin_complete_actions', [ $this, 'update_install_plugin_complete_actions' ], 10, 3 );
		add_filter( 'plugin_install_action_links', [ $this, 'disable_install_action_links' ], 10, 2 );
		add_filter( 'plugin_install_description', [ $this, 'update_plugin_install_description' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, 'install_plugin_enqueues' ], 11 );
		add_action( 'wpforms_loaded', [ $this, 'objects' ], 1 );
		add_action( 'wpforms_loaded', [ $this, 'updater' ], 30 );
		add_action( 'wpforms_install', [ $this, 'install' ] );
		add_filter( 'wpforms_settings_license_output', [ $this, 'settings_license_callback' ] );
		add_filter( 'wpforms_settings_defaults', [ $this, 'register_settings_fields' ], 5 );
		add_filter( 'wpforms_update_settings', [ $this, 'maybe_unset_gdpr_sub_settings' ] );
		add_action( 'wpforms_process_entry_save', [ $this, 'entry_save' ], 10, 4 );
		add_action( 'wpforms_form_settings_general', [ $this, 'form_settings_general' ] );
		add_filter( 'wpforms_overview_table_columns', [ $this, 'form_table_columns' ] );
		add_filter( 'wpforms_overview_table_column_value', [ $this, 'form_table_columns_value' ], 10, 3 );
		add_action( 'wpforms_form_settings_notifications', [ $this, 'form_settings_notifications' ], 8 );
		add_action( 'wpforms_form_settings_confirmations', [ $this, 'form_settings_confirmations' ] );
		add_filter( 'wpforms_frontend_strings', [ $this, 'frontend_strings' ] );
		add_action( 'admin_notices', [ $this, 'conditional_logic_addon_notice' ] );
		add_filter( 'wpforms_email_footer_text', [ $this, 'form_notification_footer' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueues' ] );
		add_filter( 'wpforms_helpers_templates_get_theme_template_paths', [ $this, 'add_templates' ] );
		add_filter( 'wpforms_integrations_usagetracking_is_enabled', '__return_true' );
		add_filter( 'wpforms_updater_perform_remote_request_before_response', [ $this, 'get_updater_response_from_cache' ], 10, 3 );
		add_filter( 'wpforms_builder_strings', [ $this, 'add_builder_strings' ], 10, 2 );
	}

	/**
	 * Setup objects.
	 *
	 * @since 1.2.1
	 */
	public function objects() {

		// Global objects.
		wpforms()->entry        = new WPForms_Entry_Handler();
		wpforms()->entry_fields = new WPForms_Entry_Fields_Handler();
		wpforms()->entry_meta   = new WPForms_Entry_Meta_Handler();

		wpforms()->register_instance( 'entry', wpforms()->entry );
		wpforms()->register_instance( 'entry_fields', wpforms()->entry_fields );
		wpforms()->register_instance( 'entry_meta', wpforms()->entry_meta );

		if ( is_admin() && ! wpforms()->obj( 'license' ) instanceof WPForms_License ) {
			wpforms()->license = new WPForms_License();

			wpforms()->register_instance( 'license', wpforms()->license );
		}
	}

	/**
	 * Load plugin updater.
	 *
	 * @since 1.0.0
	 */
	public function updater() {

		if ( ! is_admin() && ! wp_doing_cron() && ! wpforms_doing_wp_cli() ) {
			return;
		}

		$key = wpforms_get_license_key();

		// Go ahead and initialize the updater.
		$updater_obj = new WPForms_Updater(
			[
				'plugin_name' => 'WPForms',
				'plugin_slug' => 'wpforms',
				'plugin_path' => plugin_basename( WPFORMS_PLUGIN_FILE ),
				'plugin_url'  => trailingslashit( WPFORMS_PLUGIN_URL ),
				'remote_url'  => WPFORMS_UPDATER_API,
				'version'     => WPFORMS_VERSION,
				'key'         => $key,
			]
		);

		// Register the updater instance.
		wpforms()->register_instance( 'updater', $updater_obj );

		$addons_cache_obj = wpforms()->obj( 'addons_cache' );

		if ( ! $addons_cache_obj ) {
			return;
		}

		$addons = $addons_cache_obj->get();

		foreach ( $addons as $addon ) {
			// Initialize the addon updater class.
			new WPForms_Updater(
				[
					'plugin_name' => $addon['title'],
					'plugin_slug' => $addon['slug'],
					'plugin_url'  => trailingslashit( $addon['url'] ),
					'remote_url'  => WPFORMS_UPDATER_API,
					'key'         => $key,
				]
			);
		}

		/**
		 * Remove all addon updater actions.
		 * This is necessary for backward compatibility with outdated addons.
		 */
		$this->remove_action_regex( '/^WPForms/', 'wpforms_updater' );

		/**
		 * Fire an action for Addons to register their updater since we know the key is present.
		 *
		 * @since 1.5.5
		 *
		 * @param string $key License key.
		 */
		do_action( 'wpforms_updater', $key ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
	}

	/**
	 * Remove action or filter.
	 *
	 * @since 1.9.0
	 *
	 * @param string $callback_pattern Callback pattern to match. A regex matching to SomeNameSpace\SomeClass::some_method.
	 * @param string $hook_name        Action name.
	 *
	 * @noinspection PhpSameParameterValueInspection
	 */
	private function remove_action_regex( string $callback_pattern, string $hook_name = '' ) {

		global $wp_filter;

		$hook_name = $hook_name ? $hook_name : current_action();
		$hooks     = $wp_filter[ $hook_name ] ?? null;
		$callbacks = $hooks->callbacks ?? [];

		foreach ( $callbacks as $priority => $actions ) {
			foreach ( $actions as $action ) {
				$this->maybe_remove_action_regex( $callback_pattern, $hook_name, $action, $priority );
			}
		}
	}

	/**
	 * Maybe remove action.
	 *
	 * @since 1.9.0
	 *
	 * @param string $callback_pattern Callback pattern to match. A regex matching to SomeNameSpace\SomeClass::some_method.
	 * @param string $hook_name        Hook name.
	 * @param array  $action           Action data.
	 * @param int    $priority         Priority.
	 *
	 * @return void
	 */
	private function maybe_remove_action_regex( string $callback_pattern, string $hook_name, array $action, int $priority ) { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		$callback = $action['function'] ?? '';

		if ( $callback instanceof Closure ) {
			return;
		}

		if ( is_array( $callback ) ) {
			$callback_class  = is_object( $callback[0] ) ? get_class( $callback[0] ) : (string) $callback[0];
			$callback_method = (string) $callback[1];
			$callback_name   = $callback_class . '::' . $callback_method;
		} else {
			$callback_name = (string) $callback;
		}

		if ( ! preg_match( $callback_pattern, $callback_name ) ) {
			return;
		}

		remove_action( $hook_name, $callback, $priority );
	}

	/**
	 * Handle plugin installation upon activation.
	 *
	 * @since 1.2.1
	 */
	public function install() {

		DB::create_custom_tables( true );

		$license = get_option( 'wpforms_connect', false );

		if ( $license ) {
			update_option(
				'wpforms_license',
				[ 'key' => $license ]
			);

			( new WPForms_License() )->validate_key( $license );
			delete_option( 'wpforms_connect' );
		}

		$this->force_translations_update();

		// Restart the import flags for Lite Connect if needed.
		if ( class_exists( Integration::class ) ) {
			Integration::maybe_restart_import_flag();
		}

		// Wipe templates content cache.
		if ( class_exists( TemplatesCache::class ) ) {
			( new TemplatesCache() )->wipe_content_cache();
		}

		// Wipe cache of an empty templates.
		// We should do it; otherwise it's possible that some templates will appear empty after upgrading to Pro.
		if ( class_exists( TemplateSingleCache::class ) ) {
			( new TemplateSingleCache() )->wipe_empty_templates_cache();
		}
	}

	/**
	 * Force WPForms Lite languages download on Pro activation.
	 *
	 * This action will force to download any new translations for WPForms Lite
	 * right away instead of waiting for 12 hours.
	 *
	 * @since 1.6.0
	 */
	protected function force_translations_update() {

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

		$locales = array_unique( [ get_locale(), get_user_locale() ] );

		if ( count( $locales ) === 1 && $locales[0] === 'en_US' ) {
			return;
		}

		$to_update = [];

		foreach ( $locales as $locale ) {
			$to_update[] = (object) [
				'type'       => 'plugin',
				'slug'       => 'wpforms-lite',
				'language'   => $locale,
				'version'    => WPFORMS_VERSION,
				'package'    => 'https://downloads.wordpress.org/translation/plugin/wpforms-lite/' . WPFORMS_VERSION . '/' . $locale . '.zip',
				'autoupdate' => true,
			];
		}

		$upgrader = new Language_Pack_Upgrader( new Automatic_Upgrader_Skin() );

		$upgrader->bulk_upgrade( $to_update );
	}

	/**
	 * Add Pro-specific templates to the list of searchable template paths.
	 *
	 * @since 1.5.6
	 *
	 * @param array $paths Paths to templates.
	 *
	 * @return array
	 */
	public function add_templates( $paths ) {

		$paths = (array) $paths;

		$paths[102] = trailingslashit( __DIR__ . '/templates' );

		return $paths;
	}

	/**
	 * Get cached updater response.
	 *
	 * @since 1.8.7
	 *
	 * @param object $response WPForms Updater response object before request has been sent. Empty object by default.
	 * @param string $action   Action name.
	 * @param array  $body     Request body.
	 *
	 * @return object
	 */
	public function get_updater_response_from_cache( $response, string $action, array $body ) {

		$update_cache_obj = wpforms()->obj( 'license_api_plugin_update_cache' );
		$slug             = (string) ( $body['tgm-updater-plugin'] ?? '' );

		if ( ! $update_cache_obj || ! $slug ) {
			return $response;
		}

		if ( $action === 'get-plugin-update' ) {
			return (object) $update_cache_obj->get_by_slug( $slug );
		}

		return $response;
	}

	/**
	 * Add custom links to the WPForms plugin row on the Plugins page.
	 *
	 * @since 1.5.9
	 *
	 * @param array  $links       Plugin row links.
	 * @param string $plugin_file Path to the plugin file relative to the plugins' directory.
	 * @param array  $plugin_data An array of plugin data. See `get_plugin_data()`.
	 * @param string $context     The plugin context.
	 *
	 * @return array
	 * @noinspection PhpMissingParamTypeInspection
	 * @noinspection PhpUnusedParameterInspection
	 * @noinspection HtmlUnknownTarget
	 */
	public function plugin_action_links( $links, $plugin_file, $plugin_data, $context ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed

		$custom = [];

		unset( $links['wpforms-pro'], $links['wpforms-docs'] );

		if ( isset( $links['wpforms-settings'] ) ) {
			$custom['wpforms-settings'] = $links['wpforms-settings'];

			unset( $links['wpforms-settings'] );
		}

		$custom['wpforms-support'] = sprintf(
			'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
			esc_url(
				add_query_arg(
					[
						'utm_content'  => 'Support',
						'utm_campaign' => 'plugin',
						'utm_medium'   => 'all-plugins',
						'utm_source'   => 'WordPress',
					],
					'https://wpforms.com/account/support/'
				)
			),
			esc_attr__( 'Go to WPForms.com Support page', 'wpforms' ),
			esc_html__( 'Support', 'wpforms' )
		);

		$custom['wpforms-docs'] = sprintf(
			'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
			esc_url(
				add_query_arg(
					[
						'utm_content'  => 'Documentation',
						'utm_campaign' => 'plugin',
						'utm_medium'   => 'all-plugins',
						'utm_source'   => 'WordPress',
					],
					'https://wpforms.com/docs/'
				)
			),
			esc_attr__( 'Read the documentation', 'wpforms' ),
			esc_html__( 'Docs', 'wpforms' )
		);

		return array_merge( $custom, (array) $links );
	}

	/**
	 * Replace the activation link for the Lite version of WPForms.
	 * Activating the Lite version of WPForms is not allowed when the Pro version is active.
	 *
	 * @since 1.9.0
	 *
	 * @param array $links Plugin row links.
	 *
	 * @return array
	 */
	public function replace_action_links( array $links ): array {

		$links['activate'] = __( 'Inactive &mdash; You\'ve got a paid version of WPForms', 'wpforms' );

		return $links;
	}

	/**
	 * Remove the "Activate" action link for the Lite version of WPForms.
	 * Activating the Lite version of WPForms is not allowed when the Pro version is active.
	 *
	 * @since 1.9.0
	 *
	 * @param array  $install_actions An array of action links.
	 * @param object $api             Plugin API data.
	 * @param string $plugin_file     Path to the plugin file relative to the plugins' directory.
	 *
	 * @return array
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function update_install_plugin_complete_actions( array $install_actions, $api, string $plugin_file ): array {

		if ( $plugin_file !== 'wpforms-lite/wpforms.php' ) {
			return $install_actions;
		}

		unset( $install_actions['activate_plugin'] );

		return $install_actions;
	}

	/**
	 * Disable the "Install" action link for the Lite version of WPForms.
	 * Installing the Lite version of WPForms is not allowed when the Pro version is active.
	 *
	 * @since 1.9.0
	 *
	 * @param array $action_links An array of action links.
	 * @param array $plugin       Plugin data.
	 *
	 * @return array
	 */
	public function disable_install_action_links( array $action_links, array $plugin ): array {

		if ( ! $this->is_lite_installed( $plugin ) ) {
			return $action_links;
		}

		$status = install_plugin_install_status( $plugin );

		// The plugin is available for update, so do not disable the action links.
		if ( $status['status'] === 'update_available' ) {
			return $action_links;
		}

		$action_links[0] = '<button type="button" class="button button-disabled" disabled="disabled">' . __( 'Activate', 'wpforms' ) . '</button>';

		return $action_links;
	}

	/**
	 * Update the plugin install description for the Lite version of WPForms.
	 * Installing the Lite version of WPForms is not allowed when the Pro version is active.
	 *
	 * @since 1.9.0
	 *
	 * @param string $description Plugin install description.
	 * @param array  $plugin      Plugin data.
	 *
	 * @return string
	 */
	public function update_plugin_install_description( string $description, array $plugin ): string {

		if ( ! $this->is_lite_installed( $plugin ) ) {
			return $description;
		}

		$notice = sprintf( '<strong>%s</strong><br><br>', __( 'You cannot activate WPForms Lite because you have a paid version of WPForms activated.', 'wpforms' ) );

		return $notice . $description;
	}

	/**
	 * Check if the Lite version of WPForms is installed.
	 *
	 * @since 1.9.0
	 *
	 * @param array $plugin Plugin data.
	 *
	 * @return bool
	 */
	private function is_lite_installed( array $plugin ): bool {

		if ( $plugin['slug'] !== 'wpforms-lite' ) {
			return false;
		}

		$all_plugins = get_plugins();

		if ( isset( $all_plugins['wpforms-lite/wpforms.php'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Override the Settings license field callback.
	 *
	 * @since 1.7.9
	 *
	 * @param string $html HTML markup for the "Lite" plugin’s license settings section.
	 *
	 * @return string
	 * @noinspection PhpMissingParamTypeInspection
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function settings_license_callback( $html ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity, Generic.CodeAnalysis.UnusedFunctionParameter.Found

		$license      = wpforms()->obj( 'license' );
		$key          = $license->get();
		$type         = $license->type();
		$is_constant  = $license->get_key_location() === 'constant';
		$has_key      = ! empty( $key );
		$has_errors   = $has_key && $license->get_errors();
		$is_valid_key = $has_key && ! empty( $type ) && ! $has_errors;
		$no_refresh   = ! $has_key || $license->is_invalid() || $license->is_disabled();

		// Block ui when the license key used as a constant.
		$class  = $is_constant ? 'wpforms-setting-license-block-ui' : '';
		$output = '<span class="wpforms-setting-license-wrapper ' . $class . '">'; // Reset the original output from the Lite version.

		$class   = $is_valid_key ? 'wpforms-setting-license-is-valid' : 'wpforms-setting-license-is-invalid';
        $class   = $has_key ? $class : '';
		$output .= '<input type="password" spellcheck="false" id="wpforms-setting-license-key" class="' . $class . '" value="' . esc_attr( $key ) . '"' . disabled( true, $has_key, false ) . '>';
		$output .= '<i></i>';
		$output .= '</span>';

		// Offer interactions when license is not defined as a constant.
		if ( ! $is_constant ) {
			$class   = $has_key ? 'wpforms-hide' : '';
			$output .= '<button id="wpforms-setting-license-key-verify" class="wpforms-btn wpforms-btn-md wpforms-btn-blue ' . $class . '">' . esc_html__( 'Verify Key', 'wpforms' ) . '</button>';
		}

		// Skip, in case the license did not expire.
		if ( $has_errors && $license->is_expired() ) {
			$renew_url = wpforms_utm_link( 'https://wpforms.com/account/licenses/', 'settings-license', 'Renew License CTA' );
			$output   .= '<a href="' . esc_url( $renew_url ) . '" id="wpforms-setting-license-key-renew" class="wpforms-btn wpforms-btn-md wpforms-btn-red wpforms-license-key-deactivate-remove" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Renew License', 'wpforms' ) . '</a>';
		}

		// Offer interactions when license is not defined as a constant.
		if ( ! $is_constant ) {
			$class   = ! $has_key ? 'wpforms-hide' : '';
			$output .= '<button id="wpforms-setting-license-key-deactivate" class="wpforms-btn wpforms-btn-md wpforms-btn-light-grey ' . $class . '">' . esc_html__( 'Remove Key', 'wpforms' ) . '</button>';
		}

		$class   = $is_valid_key ? 'wpforms-hide' : '';
		$output .= '<p id="wpforms-setting-license-key-info-message" class="' . $class . '">' . $license->get_info_message_escaped() . '</p>';

		// If we have previously looked up the license type, display it.
		$class   = ! $is_valid_key ? 'wpforms-hide' : '';
		$output .= '<p class="type ' . $class . '">' .
					sprintf( /* translators: $s - license type. */
						esc_html__( 'Your license key level is %s.', 'wpforms' ),
						'<strong>' . esc_html( ucwords( $type ) ) . '</strong>'
					) .
					'</p>';
		$class   = $no_refresh ? 'wpforms-hide' : '';
		$output .= '<p class="desc ' . $class . '">' .
					sprintf( /* translators: %s - refresh link. */
						esc_html__( 'If your license has been upgraded or is incorrect, then please %1$sforce a refresh%2$s.', 'wpforms' ),
						'<a href="#" id="wpforms-setting-license-key-refresh">',
						'</a>'
					)
					. '</p>';

		return $output;
	}

	/**
	 * Install plugin enqueues.
	 *
	 * @since 1.9.0
	 */
	public function install_plugin_enqueues() {

		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( $screen !== null && ! in_array( $screen->id, [ 'plugins', 'plugin-install' ], true ) ) {
			return;
		}

		$min = wpforms_get_min_suffix();

		wp_enqueue_style(
			'wpforms-pro-install',
			WPFORMS_PLUGIN_URL . "assets/pro/css/install{$min}.css",
			[],
			WPFORMS_VERSION
		);

		wp_enqueue_script(
			'wpforms-pro-install',
			WPFORMS_PLUGIN_URL . "assets/pro/js/admin/install{$min}.js",
			[ 'jquery' ],
			WPFORMS_VERSION,
			true
		);

		wp_localize_script(
			'wpforms-pro-install',
			'wpforms_install_data',
			[
				'activate'            => __( 'Activate', 'wpforms' ),
				'lite_version_notice' => __( 'You cannot activate WPForms Lite because you have a paid version of WPForms activated.', 'wpforms' ),
			]
		);
	}

	/**
	 * Pro admin scripts and styles.
	 *
	 * @since 1.5.5
	 */
	public function admin_enqueues() {

		if ( ! wpforms_is_admin_page() ) {
			return;
		}

		$min = wpforms_get_min_suffix();

		// Pro admin styles.
		wp_enqueue_style(
			'wpforms-pro-admin',
			WPFORMS_PLUGIN_URL . "assets/pro/css/admin{$min}.css",
			[],
			WPFORMS_VERSION
		);
	}

	/**
	 * Register Pro settings fields.
	 *
	 * @since 1.3.9
	 *
	 * @param array $settings Admin area settings list.
	 *
	 * @return array
	 */
	public function register_settings_fields( $settings ) {

		// Validation settings for fields only available in Pro.
		$settings['validation']['validation-url']              = [
			'id'      => 'validation-url',
			'name'    => esc_html__( 'Website URL', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'Please enter a valid URL.', 'wpforms' ),
		];
		$settings['validation']['validation-phone']            = [
			'id'      => 'validation-phone',
			'name'    => esc_html__( 'Phone', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'Please enter a valid phone number.', 'wpforms' ),
		];
		$settings['validation']['validation-fileextension']    = [
			'id'      => 'validation-fileextension',
			'name'    => esc_html__( 'File Extension', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'File type is not allowed.', 'wpforms' ),
		];
		$settings['validation']['validation-filesize']         = [
			'id'      => 'validation-filesize',
			'name'    => esc_html__( 'File Size', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'File exceeds max size allowed. File was not uploaded.', 'wpforms' ),
		];
		$settings['validation']['validation-maxfilenumber']    = [
			'id'      => 'validation-maxfilenumber',
			'name'    => esc_html__( 'File Uploads', 'wpforms' ),
			'type'    => 'text',
			'default' => sprintf( /* translators: %s - max number of files allowed. */
				esc_html__( 'File uploads exceed the maximum number allowed (%s).', 'wpforms' ),
				'{fileLimit}'
			),
		];
		$settings['validation']['validation-time12h']          = [
			'id'      => 'validation-time12h',
			'name'    => esc_html__( 'Time (12 hour)', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'Please enter time in 12-hour AM/PM format (eg 8:45 AM).', 'wpforms' ),
		];
		$settings['validation']['validation-time24h']          = [
			'id'      => 'validation-time24h',
			'name'    => esc_html__( 'Time (24 hour)', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'Please enter time in 24-hour format (eg 22:45).', 'wpforms' ),
		];
		$settings['validation']['validation-time-limit']       = [
			'id'      => 'validation-time-limit',
			'name'    => esc_html__( 'Limit Hours', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'Please enter time between {minTime} and {maxTime}.', 'wpforms' ),
		];
		$settings['validation']['validation-post_max_size']    = [
			'id'      => 'validation-post_max_size',
			'name'    => esc_html__( 'File Upload Total Size', 'wpforms' ),
			'type'    => 'text',
			'default' => sprintf( /* translators: %1$s - total size of the selected files in megabytes, %2$s - allowed file upload limit in megabytes. */
				esc_html__( 'The total size of the selected files %1$s MB exceeds the allowed limit %2$s MB.', 'wpforms' ),
				'{totalSize}',
				'{maxSize}'
			),
		];
		$settings['validation']['validation-passwordstrength'] = [
			'id'      => 'validation-passwordstrength',
			'name'    => esc_html__( 'Password Strength', 'wpforms' ),
			'type'    => 'text',
			'default' => esc_html__( 'A stronger password is required. Consider using upper and lower case letters, numbers, and symbols.', 'wpforms' ),
		];

		// Additional GDPR related options.
		$settings['general'] = wpforms_array_insert(
			$settings['general'],
			[
				'gdpr-disable-uuid'    => [
					'id'        => 'gdpr-disable-uuid',
					'name'      => esc_html__( 'Disable User Cookies', 'wpforms' ),
					'desc'      => esc_html__( 'Disable user tracking cookies. This will disable the Related Entries feature and the Form Abandonment addon.', 'wpforms' ),
					'type'      => 'toggle',
					'is_hidden' => ! wpforms_setting( 'gdpr' ),
					'status'    => true,
				],
				'gdpr-disable-details' => [
					'id'        => 'gdpr-disable-details',
					'name'      => esc_html__( 'Disable User Details', 'wpforms' ),
					'desc'      => esc_html__( 'Disable storage IP addresses and User Agent on all forms. If unchecked, then this can be managed on a form-by-form basis inside the form builder under Settings → General', 'wpforms' ),
					'type'      => 'toggle',
					'is_hidden' => ! wpforms_setting( 'gdpr' ),
					'status'    => true,
				],
			],
			'gdpr'
		);

		unset( $settings['misc'][ UsageTracking::SETTINGS_SLUG ] );

		return $settings;
	}

	/**
	 * Modify GDPR sub-settings before they are persisted in the database.
	 *
	 * Disabling GDPR master switch doesn't modify sub-settings by default. Although we should
	 * always check for both parent and child settings, unsetting them when the master switch
	 * is off is the right thing to do.
	 *
	 * @since 1.7.5
	 *
	 * @param array $settings An array of plugin settings to modify.
	 *
	 * @return array
	 */
	public function maybe_unset_gdpr_sub_settings( $settings ) {

		$settings['gdpr'] = $settings['gdpr'] ?? false;

		if ( ! $settings['gdpr'] ) {
			$settings['gdpr-disable-uuid']    = false;
			$settings['gdpr-disable-details'] = false;
		}

		return $settings;
	}

	/**
	 * Save entry to the database.
	 *
	 * @since 1.2.1
	 *
	 * @param array      $fields    List of form fields.
	 * @param array      $entry     User submitted data.
	 * @param int|string $form_id   Form ID.
	 * @param array      $form_data Prepared form settings.
	 */
	public function entry_save( $fields, $entry, $form_id, $form_data = [] ) {

		// Check if a form has entries disabled.
		if ( isset( $form_data['settings']['disable_entries'] ) ) {
			return;
		}

		// Register the Submission class.
		$submission = wpforms()->obj( 'submission' );

		$submission->register( $fields, $entry, $form_id, $form_data );

		// Prepare the entry data.
		$entry_args = $submission->prepare_entry_data();

		// Create entry.
		$entry_id = wpforms()->obj( 'entry' )->add( $entry_args );

		// Create fields.
		$submission->create_fields( $entry_id );
	}

	/**
	 * Add additional form settings to the General section.
	 *
	 * @since 1.2.1
	 *
	 * @param WPForms_Builder_Panel_Settings $instance Settings management panel instance.
	 */
	public function form_settings_general( $instance ) {

		// Don't provide this option if the user has configured payments.
		if (
			isset( $instance->form_data['settings']['disable_entries'] ) ||
			! wpforms_has_payment_gateway( $instance->form_data )
		) {
			wpforms_panel_field(
				'toggle',
				'settings',
				'disable_entries',
				$instance->form_data,
				esc_html__( 'Disable storing entry information in WordPress', 'wpforms' )
			);
		}

		// Only provide this option if GDPR enhancements are enabled and user
		// details are not disabled globally.
		if ( wpforms_setting( 'gdpr' ) && ! wpforms_setting( 'gdpr-disable-details' ) ) {
			wpforms_panel_field(
				'toggle',
				'settings',
				'disable_ip',
				$instance->form_data,
				esc_html__( 'Disable storing user details (IP address and user agent)', 'wpforms' )
			);
		}
	}

	/**
	 * Add entry counts column to form table.
	 *
	 * @since 1.2.1
	 *
	 * @param array $columns List of table columns.
	 *
	 * @return array
	 */
	public function form_table_columns( $columns ) {

		if ( ! wpforms_current_user_can( 'view_entries' ) ) {
			unset( $columns['entries'] );

			return $columns;
		}

		// The label for the "Entries" column is already defined in the "Lite" version.
		// The primary reason for leaving this here is to continue loading the translation equivalent associated with the PRO version text-domain.
		if ( isset( $columns['entries'] ) ) {
			$columns['entries'] = esc_html__( 'Entries', 'wpforms' );
		}

		return $columns;
	}

	/**
	 * Add entry counts value to entry count column.
	 *
	 * @since 1.2.1
	 *
	 * @param string $value       Value.
	 * @param object $form        Form.
	 * @param string $column_name Column name.
	 *
	 * @return string
	 * @noinspection HtmlUnknownTarget
	 */
	public function form_table_columns_value( $value, $form, $column_name ) {

		if ( $column_name !== 'entries' ) {
			return $value;
		}

		if ( ! wpforms_current_user_can( 'view_entries_form_single', $form->ID ) ) {
			return '&mdash;';
		}

		$form_data = wpforms_decode( $form->post_content );
		$count     = wpforms()->obj( 'entry' )->get_entries(
			[
				'form_id' => $form->ID,
			],
			true
		);

		if ( $count === 0 && ! empty( $form_data['settings']['disable_entries'] ) ) {
			return '&mdash;';
		}

		return sprintf(
			'<a href="%s">%d</a>',
			add_query_arg(
				[
					'view'    => 'list',
					'form_id' => $form->ID,
				],
				admin_url( 'admin.php?page=wpforms-entries' )
			),
			$count
		);
	}

	/**
	 * Form notification settings, supports multiple notifications.
	 *
	 * @since 1.2.3
	 *
	 * @param object $settings Settings.
	 *
	 * @noinspection HtmlUnknownTarget
	 */
	public function form_settings_notifications( $settings ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$cc            = wpforms_setting( 'email-carbon-copy' );
		$form_settings = ! empty( $settings->form_data['settings'] ) ? $settings->form_data['settings'] : [];
		$notifications = is_array( $form_settings ) && isset( $form_settings['notifications'] ) ? $form_settings['notifications'] : [];
		$from_email    = '{admin_email}';
		$from_name     = sanitize_text_field( get_option( 'blogname' ) );

		// Fetch next ID and handle backwards compatibility.
		if ( empty( $notifications ) ) {
			$next_id = 2;

			$notifications[1]['subject']        = ! empty( $form_settings['notification_subject'] ) ?
				$form_settings['notification_subject'] :
				sprintf( /* translators: %s - form name. */
					esc_html__( 'New %s Entry', 'wpforms' ),
					$settings->form->post_title
				);
			$notifications[1]['email']          = ! empty( $form_settings['notification_email'] ) ? $form_settings['notification_email'] : '{admin_email}';
			$notifications[1]['sender_name']    = ! empty( $form_settings['notification_fromname'] ) ? $form_settings['notification_fromname'] : $from_name;
			$notifications[1]['sender_address'] = ! empty( $form_settings['notification_fromaddress'] ) ? $form_settings['notification_fromaddress'] : $from_email;
			$notifications[1]['replyto']        = ! empty( $form_settings['notification_replyto'] ) ? $form_settings['notification_replyto'] : '';
		} else {
			$next_id = max( array_keys( $notifications ) ) + 1;
		}

		$default_notifications_key = min( array_keys( $notifications ) );

		$hidden = empty( $settings->form_data['settings']['notification_enable'] ) ? 'wpforms-hidden' : '';

		echo '<div class="wpforms-panel-content-section-title">';
			echo '<span id="wpforms-builder-settings-notifications-title">';
				esc_html_e( 'Notifications', 'wpforms' );
			echo '</span>';
			echo '<button class="wpforms-notifications-add wpforms-builder-settings-block-add ' . esc_attr( $hidden ) . '" data-block-type="notification" data-next-id="' . absint( $next_id ) . '">' . esc_html__( 'Add New Notification', 'wpforms' ) . '</button>';// phpcs:ignore
		echo '</div>';

		$dismissed = get_user_meta( get_current_user_id(), 'wpforms_dismissed', true );

		if ( empty( $dismissed['edu-builder-notifications-description'] ) ) {
			echo '<div class="wpforms-panel-content-section-description wpforms-dismiss-container wpforms-dismiss-out">';
			echo '<button type="button" class="wpforms-dismiss-button" title="' . esc_attr__( 'Dismiss this message.', 'wpforms' ) . '" data-section="builder-notifications-description"></button>';
			echo '<p>';
			printf(
				wp_kses( /* translators: %s - link to the WPForms.com doc article. */
					__( 'Notifications are emails sent when a form is submitted. By default, these emails include entry details. For setup and customization options, including a video overview, please <a href="%s" target="_blank" rel="noopener noreferrer">see our tutorial</a>.', 'wpforms' ),
					[
						'a' => [
							'href'   => [],
							'rel'    => [],
							'target' => [],
						],
					]
				),
				esc_url( wpforms_utm_link( 'https://wpforms.com/docs/setup-form-notification-wpforms/', 'Builder Notifications', 'Form Notifications Documentation' ) )
			);
			echo '</p>';
			echo '<p>';
			printf(
				wp_kses( /* translators: 1$s, %2$s - links to the WPForms.com doc articles. */
					__( 'After saving these settings, be sure to <a href="%1$s" target="_blank" rel="noopener noreferrer">test a form submission</a>. This lets you see how emails will look, and to ensure that they <a href="%2$s" target="_blank" rel="noopener noreferrer">are delivered successfully</a>.', 'wpforms' ),
					[
						'a'  => [
							'href'   => [],
							'rel'    => [],
							'target' => [],
						],
						'br' => [],
					]
				),
				esc_url( wpforms_utm_link( 'https://wpforms.com/docs/how-to-properly-test-your-wordpress-forms-before-launching-checklist/', 'Builder Notifications', 'Testing A Form Documentation' ) ),
				esc_url( wpforms_utm_link( 'https://wpforms.com/docs/troubleshooting-email-notifications/', 'Builder Notifications', 'Troubleshoot Notifications Documentation' ) )
			);
			echo '</p>';
			echo '</div>';
		}

		wpforms_panel_field(
			'toggle',
			'settings',
			'notification_enable',
			$settings->form_data,
			esc_html__( 'Enable Notifications', 'wpforms' ),
			[
				'value' => empty( $form_settings['notification_enable'] ) ? 0 : 1,
			]
		);

		foreach ( $notifications as $id => $notification ) {

			$name          = ! empty( $notification['notification_name'] ) ? $notification['notification_name'] : esc_html__( 'Default Notification', 'wpforms' );
			$closed_state  = '';
			$toggle_state  = '<i class="fa fa-chevron-circle-up"></i>';
			$block_classes = 'wpforms-notification wpforms-builder-settings-block';

			// phpcs:disable WPForms.PHP.ValidateHooks.InvalidHookName
			/**
			 * Allow filtering of text after the `From Name` field.
			 *
			 * @since 1.2.3
			 * @since 1.7.6 Added $form_data and $id arguments.
			 *
			 * @param string $value     Value to be filtered.
			 * @param array  $form_data Form data.
			 * @param int    $id        Notification ID.
			 */
			$from_name_after = apply_filters( 'wpforms_builder_notifications_from_name_after', '', $settings->form_data, $id );

			/**
			 * Allow filtering of a text after the `From Email` field.
			 *
			 * @since 1.2.3
			 * @since 1.7.6 Added $form_data and $id arguments.
			 *
			 * @param array $value     Value to be filtered.
			 * @param array $form_data Form data.
			 * @param int   $id        Notification ID.
			 */
			$from_email_after = apply_filters( 'wpforms_builder_notifications_from_email_after', '', $settings->form_data, $id );
			// phpcs:enable WPForms.PHP.ValidateHooks.InvalidHookName

			if ( ! empty( $settings->form_data['id'] ) && 'closed' === wpforms_builder_settings_block_get_state( $settings->form_data['id'], $id, 'notification' ) ) {
				$closed_state = 'style="display:none"';
				$toggle_state = '<i class="fa fa-chevron-circle-down"></i>';
			}

			if ( $default_notifications_key === $id ) {
				$block_classes .= ' wpforms-builder-settings-block-default';
			}

			do_action( 'wpforms_form_settings_notifications_single_before', $settings, $id );
			?>

			<div class="<?php echo esc_attr( $block_classes ); ?>" data-block-type="notification" data-block-id="<?php echo absint( $id ); ?>">

				<div class="wpforms-builder-settings-block-header">
					<div class="wpforms-builder-settings-block-actions">
						<?php
							/**
							 * Fires before rendering a single notification action in the form settings.
							 *
							 * @since 1.4.1.1
							 *
							 * @param int    $id           Notification ID.
							 * @param array  $notification Notification data.
							 * @param object $settings     Form settings object.
							 */
							do_action( 'wpforms_form_settings_notifications_single_action', $id, $notification, $settings ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
						?>

						<?php $this->get_status_button( $notification, $form_settings, $id ); ?>

						<button class="wpforms-builder-settings-block-clone" title="<?php esc_attr_e( 'Clone', 'wpforms' ); ?>"><i class="fa fa-copy"></i></button><!--
						--><button class="wpforms-builder-settings-block-delete" title="<?php esc_attr_e( 'Delete', 'wpforms' ); ?>"><i class="fa fa-trash-o"></i></button><!--
						--><button class="wpforms-builder-settings-block-toggle" title="<?php esc_attr_e( 'Open / Close', 'wpforms' ); ?>">
							<?php echo $toggle_state; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
					</div>

					<div class="wpforms-builder-settings-block-name-holder">
						<span class="wpforms-builder-settings-block-name">
							<?php echo esc_html( $name ); ?>
						</span>
						<div class="wpforms-builder-settings-block-name-edit">
							<input type="text" name="settings[notifications][<?php echo absint( $id ); ?>][notification_name]" value="<?php echo esc_attr( $name ); ?>">
						</div>
						<button class="wpforms-builder-settings-block-edit" title="<?php esc_attr_e( 'Edit', 'wpforms' ); ?>"><i class="fa fa-pencil"></i></button>
					</div>
				</div>

				<div class="wpforms-builder-settings-block-content" <?php echo $closed_state; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php
					wpforms_panel_field(
						'text',
						'notifications',
						'email',
						$settings->form_data,
						esc_html__( 'Send To Email Address', 'wpforms' ),
						[
							'default'     => '{admin_email}',
							'tooltip'     => esc_html__( 'Enter the email address to receive form entry notifications. For multiple notifications, separate email addresses with a comma.', 'wpforms' ),
							'smarttags'   => [
								'type'                  => 'fields',
								'fields'                => 'email',
								'allow-repeated-fields' => true,
							],
							'parent'      => 'settings',
							'subsection'  => $id,
							'input_id'    => 'wpforms-panel-field-notifications-email-' . $id,
							'input_class' => 'wpforms-smart-tags-enabled',
							'class'       => 'email-recipient',
						]
					);
					if ( $cc ) :
						wpforms_panel_field(
							'text',
							'notifications',
							'carboncopy',
							$settings->form_data,
							esc_html__( 'CC', 'wpforms' ),
							[
								'tooltip'     => esc_html__( 'Enter the email address to add it to the carbon copy of the form entry notifications. For multiple notifications, separate email addresses with a comma.', 'wpforms' ),
								'smarttags'   => [
									'type'   => 'fields',
									'fields' => 'email',
									'allow-repeated-fields' => true,
								],
								'parent'      => 'settings',
								'subsection'  => $id,
								'input_id'    => 'wpforms-panel-field-notifications-carboncopy-' . $id,
								'input_class' => 'wpforms-smart-tags-enabled',
							]
						);
					endif;
					wpforms_panel_field(
						'text',
						'notifications',
						'subject',
						$settings->form_data,
						esc_html__( 'Email Subject Line', 'wpforms' ),
						[
							'default'     => sprintf( /* translators: %s - form name. */
								esc_html__( 'New Entry: %s', 'wpforms' ),
								$settings->form->post_title
							),
							'smarttags'   => [
								'type' => 'all',
							],
							'parent'      => 'settings',
							'subsection'  => $id,
							'input_id'    => 'wpforms-panel-field-notifications-subject-' . $id,
							'input_class' => 'wpforms-smart-tags-enabled',
						]
					);
					wpforms_panel_field(
						'text',
						'notifications',
						'sender_name',
						$settings->form_data,
						esc_html__( 'From Name', 'wpforms' ),
						// phpcs:disable WPForms.PHP.ValidateHooks.InvalidHookName
						/**
						 * Allow modifying the "From Name" field settings in the builder on Settings > Notifications panel.
						 *
						 * @since 1.7.6
						 *
						 * @param array $args      Field settings.
						 * @param array $form_data Form data.
						 * @param int   $id        Notification ID.
						 */
						apply_filters(
							'wpforms_builder_notifications_sender_name_settings',
							[
								'default'     => $from_name,
								'smarttags'   => [
									'type'   => 'fields',
									'fields' => 'name,text',
								],
								'parent'      => 'settings',
								'subsection'  => $id,
								'input_id'    => 'wpforms-panel-field-notifications-sender_name-' . $id,
								'input_class' => 'wpforms-smart-tags-enabled',
							],
							$settings->form_data,
							$id
						)
					// phpcs:enable WPForms.PHP.ValidateHooks.InvalidHookName
					);
					wpforms_panel_field(
						'text',
						'notifications',
						'sender_address',
						$settings->form_data,
						esc_html__( 'From Email', 'wpforms' ),
						// phpcs:disable WPForms.PHP.ValidateHooks.InvalidHookName
						/**
						 * Allow modifying the "From Email" field settings in the builder on the Settings > Notifications panel.
						 *
						 * @since 1.7.6
						 *
						 * @param array $args      Field settings.
						 * @param array $form_data Form data.
						 * @param int   $id        Notification ID.
						 */
						apply_filters(
							'wpforms_builder_notifications_sender_address_settings',
							[
								'default'     => $from_email,
								'smarttags'   => [
									'type'   => 'fields',
									'fields' => 'email',
								],
								'parent'      => 'settings',
								'subsection'  => $id,
								'input_id'    => 'wpforms-panel-field-notifications-sender_address-' . $id,
								'input_class' => 'wpforms-smart-tags-enabled',
							],
							$settings->form_data,
							$id
						)
						// phpcs:enable WPForms.PHP.ValidateHooks.InvalidHookName
					);
					wpforms_panel_field(
						'text',
						'notifications',
						'replyto',
						$settings->form_data,
						esc_html__( 'Reply-To', 'wpforms' ),
						[
							'tooltip'     => esc_html(
								sprintf( /* translators: %s - <email@example.com>. */
									__( 'Enter the email address or email address with recipient\'s name in "First Last %s" format.', 'wpforms' ),
									// &#8203 is a zero-width space character. Without it, Tooltipster thinks it's an HTML tag
									// and closes it at the end of the string, hiding everything after this value.
									'<&#8203;email@example.com&#8203;>'
								)
							),
							'smarttags'   => [
								'type'   => 'fields',
								'fields' => 'email,name',
							],
							'parent'      => 'settings',
							'subsection'  => $id,
							'input_id'    => 'wpforms-panel-field-notifications-replyto-' . $id,
							'input_class' => 'wpforms-smart-tags-enabled',
						]
					);
					wpforms_panel_field(
						'textarea',
						'notifications',
						'message',
						$settings->form_data,
						esc_html__( 'Email Message', 'wpforms' ),
						[
							'rows'        => 6,
							'default'     => '{all_fields}',
							'smarttags'   => [
								'type' => 'all',
							],
							'parent'      => 'settings',
							'subsection'  => $id,
							'input_id'    => 'wpforms-panel-field-notifications-message-' . $id,
							'input_class' => 'wpforms-smart-tags-enabled',
							'class'       => 'email-msg',
							/* translators: %s - all fields smart tag. */
							'after'       => '<p class="note">' . sprintf( esc_html__( 'To display all form fields, use the %s Smart Tag.', 'wpforms' ), '<code>{all_fields}</code>' ) . '</p>',
						]
					);

					wpforms_conditional_logic()->builder_block(
						[
							'form'        => $settings->form_data,
							'type'        => 'panel',
							'panel'       => 'notifications',
							'parent'      => 'settings',
							'subsection'  => $id,
							'actions'     => [
								'go'   => esc_html__( 'Send', 'wpforms' ),
								'stop' => esc_html__( 'Don\'t send', 'wpforms' ),
							],
							'action_desc' => esc_html__( 'this notification if', 'wpforms' ),
							'reference'   => esc_html__( 'Email notifications', 'wpforms' ),
						]
					);

					// Hook for addons.

					// phpcs:disable WPForms.PHP.ValidateHooks.InvalidHookName

					/**
					 * Fires after notification block.
					 *
					 * @since 1.3.3
					 *
					 * @param array $settings Current confirmation data.
					 * @param int   $id       Notification id.
					 */
					do_action( 'wpforms_form_settings_notifications_single_after', $settings, $id );

					// phpcs:enable WPForms.PHP.ValidateHooks.InvalidHookName
					?>
				</div><!-- /.wpforms-builder-settings-block-content -->
			</div><!-- /.wpforms-builder-settings-block -->
			<?php
		}
	}

	/**
	 * Get status button.
	 *
	 * @since 1.9.5
	 *
	 * @param array $notification  Notification data.
	 * @param array $form_settings Form settings.
	 * @param int   $id            Notification ID.
	 */
	private function get_status_button( array $notification, array $form_settings, $id ): void {

		$is_active = ! empty( $form_settings['notification_enable'] ) && ( ! isset( $notification['enable'] ) || (int) $notification['enable'] === 1 );

		printf(
			'<span class="wpforms-builder-settings-block-status wpforms-badge wpforms-badge-sm wpforms-badge-%1$s wpforms-status-button" title="%5$s" data-active="%2$s">%3$s<i class="wpforms-status-label">%4$s</i></span>',
			sanitize_html_class( $is_active ? 'green' : 'silver' ),
			esc_attr( $is_active ),
			$is_active ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>',
			esc_html( $this->get_button_label( $is_active ) ),
			esc_attr( $this->get_button_title( $is_active ) )
		);

		// BC: The notification should be enabled even when the `enabled` key doesn't exist.
		// The key is missed for old forms or forms created using the Lite version.
		$notification_enabled = ! isset( $notification['enable'] ) || (int) $notification['enable'] === 1;

		printf( '<input type="hidden" name="settings[notifications][%1$d][enable]" id="wpforms-panel-field-notifications-%1$d-enable" value="%2$d">', (int) $id, esc_attr( $notification_enabled ) );
	}


	/**
	 * Get the label for the status button.
	 *
	 * @since 1.9.5
	 *
	 * @param bool $is_active Whether the notification is active.
	 *
	 * @return string The label for the status button.
	 */
	private function get_button_label( bool $is_active ): string {

		$status_strings = $this->get_status_button_strings();

		return $is_active ? $status_strings['active'] : $status_strings['inactive'];
	}

	/**
	 * Get the title for the status button.
	 *
	 * @since 1.9.5
	 *
	 * @param bool $is_active Whether the notification is active.
	 *
	 * @return string The title for the status button.
	 */
	private function get_button_title( bool $is_active ): string {

		$status_strings = $this->get_status_button_strings();

		return $is_active ? $status_strings['deactivate'] : $status_strings['activate'];
	}

	/**
	 * Form confirmation settings, supports multiple confirmations.
	 *
	 * @since 1.4.8
	 *
	 * @param WPForms_Builder_Panel_Settings $settings Builder panel settings.
	 */
	public function form_settings_confirmations( $settings ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		wp_enqueue_editor();

		$form_settings = ! empty( $settings->form_data['settings'] ) ? $settings->form_data['settings'] : [];
		$confirmations = is_array( $form_settings ) && isset( $form_settings['confirmations'] ) ? $form_settings['confirmations'] : [];

		// Fetch next ID and handle backwards compatibility.
		if ( empty( $confirmations ) ) {
			$next_id = 2;

			$confirmations[1]['type']           = ! empty( $form_settings['confirmation_type'] ) ? $form_settings['confirmation_type'] : 'message';
			$confirmations[1]['message']        = ! empty( $form_settings['confirmation_message'] ) ? $form_settings['confirmation_message'] : esc_html__( 'Thanks for contacting us! We will be in touch with you shortly.', 'wpforms' );
			$confirmations[1]['message_scroll'] = ! empty( $form_settings['confirmation_message_scroll'] ) ? $form_settings['confirmation_message_scroll'] : 1;
			$confirmations[1]['page']           = ! empty( $form_settings['confirmation_page'] ) ? $form_settings['confirmation_page'] : '';
			$confirmations[1]['redirect']       = ! empty( $form_settings['confirmation_redirect'] ) ? $form_settings['confirmation_redirect'] : '';

			$settings->form_data['settings']['confirmations'] = $confirmations;
		} else {
			$next_id = max( array_keys( $confirmations ) ) + 1;
		}

		$default_confirmation_key = min( array_keys( $confirmations ) );

		echo '<div class="wpforms-panel-content-section-title">';
		esc_html_e( 'Confirmations', 'wpforms' );
		echo '<button class="wpforms-confirmation-add wpforms-builder-settings-block-add" data-block-type="confirmation" data-next-id="' . absint( $next_id ) . '">' . esc_html__( 'Add New Confirmation', 'wpforms' ) . '</button>';
		echo '</div>';

		foreach ( $confirmations as $field_id => $confirmation ) {

			$name          = ! empty( $confirmation['name'] ) ? $confirmation['name'] : esc_html__( 'Default Confirmation', 'wpforms' );
			$closed_state  = '';
			$toggle_state  = '<i class="fa fa-chevron-circle-up"></i>';
			$block_classes = 'wpforms-confirmation wpforms-builder-settings-block';

			if ( $default_confirmation_key === $field_id ) {
				$block_classes .= ' wpforms-builder-settings-block-default';
			}

			if ( ! empty( $settings->form_data['id'] ) && 'closed' === wpforms_builder_settings_block_get_state( $settings->form_data['id'], $field_id, 'confirmation' ) ) {
				$closed_state = 'style="display:none"';
				$toggle_state = '<i class="fa fa-chevron-circle-down"></i>';
			}

			/**
			 * Fires before each confirmation to add custom fields.
			 *
			 * @since 1.4.8
			 *
			 * @param WPForms_Builder_Panel_Settings $settings Builder panel settings.
			 * @param int                            $field_id Field ID.
			 */
			do_action( 'wpforms_form_settings_confirmations_single_before', $settings, $field_id );
			?>

			<div class="<?php echo esc_attr( $block_classes ); ?>" data-block-type="confirmation" data-block-id="<?php echo wpforms_validate_field_id( $field_id ); ?>">

				<div class="wpforms-builder-settings-block-header">
					<div class="wpforms-builder-settings-block-actions">
						<?php
							/**
							 * Fires before rendering a single confirmation action in the form settings.
							 *
							 * @since 1.4.8
							 *
							 * @param int    $field_id     Confirmation ID.
							 * @param array  $confirmation Confirmation data.
							 * @param object $settings     Form settings object.
							 */
							do_action( 'wpforms_form_settings_confirmations_single_action', $field_id, $confirmation, $settings ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
						?>

						<button class="wpforms-builder-settings-block-delete" title="<?php esc_attr_e( 'Delete', 'wpforms' ); ?>"><i class="fa fa-trash-o"></i></button><!--
						--><button class="wpforms-builder-settings-block-toggle" title="<?php esc_attr_e( 'Open / Close', 'wpforms' ); ?>">
							<?php echo $toggle_state; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
					</div>

					<div class="wpforms-builder-settings-block-name-holder">
						<span class="wpforms-builder-settings-block-name"><?php echo esc_html( $name ); ?></span>

						<div class="wpforms-builder-settings-block-name-edit">
							<input type="text" name="settings[confirmations][<?php echo wpforms_validate_field_id( $field_id ); ?>][name]" value="<?php echo esc_attr( $name ); ?>">
						</div>
						<button class="wpforms-builder-settings-block-edit" title="<?php esc_attr_e( 'Edit', 'wpforms' ); ?>"><i class="fa fa-pencil"></i></button>
					</div>
				</div>

				<div class="wpforms-builder-settings-block-content" <?php echo $closed_state; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

					<?php
					wpforms_panel_field(
						'select',
						'confirmations',
						'type',
						$settings->form_data,
						esc_html__( 'Confirmation Type', 'wpforms' ),
						[
							'default'     => 'message',
							'options'     => [
								'message'  => esc_html__( 'Message', 'wpforms' ),
								'page'     => esc_html__( 'Show Page', 'wpforms' ),
								'redirect' => esc_html__( 'Go to URL (Redirect)', 'wpforms' ),
							],
							'class'       => 'wpforms-panel-field-confirmations-type-wrap',
							'input_id'    => 'wpforms-panel-field-confirmations-type-' . $field_id,
							'input_class' => 'wpforms-panel-field-confirmations-type',
							'parent'      => 'settings',
							'subsection'  => $field_id,
						]
					);

					wpforms_panel_field(
						'textarea',
						'confirmations',
						'message',
						$settings->form_data,
						esc_html__( 'Confirmation Message', 'wpforms' ),
						[
							'default'     => esc_html__( 'Thanks for contacting us! We will be in touch with you shortly.', 'wpforms' ),
							'tinymce'     => [
								'editor_height' => '200',
							],
							'input_id'    => 'wpforms-panel-field-confirmations-message-' . $field_id,
							'input_class' => 'wpforms-panel-field-confirmations-message',
							'parent'      => 'settings',
							'subsection'  => $field_id,
							'class'       => 'wpforms-panel-field-tinymce',
							'smarttags'   => [
								'type' => 'all',
							],
							'location'    => 'confirmations',
						]
					);

					wpforms_panel_field(
						'toggle',
						'confirmations',
						'message_scroll',
						$settings->form_data,
						esc_html__( 'Automatically scroll to the confirmation message', 'wpforms' ),
						[
							'input_id'    => 'wpforms-panel-field-confirmations-message_scroll-' . $field_id,
							'input_class' => 'wpforms-panel-field-confirmations-message_scroll',
							'parent'      => 'settings',
							'subsection'  => $field_id,
						]
					);

					wpforms_panel_field(
						'select',
						'confirmations',
						'page',
						$settings->form_data,
						esc_html__( 'Confirmation Page', 'wpforms' ),
						[
							'class'       => 'wpforms-panel-field-confirmations-page-choicesjs',
							'options'     => wpforms_builder_form_settings_confirmation_get_pages( $settings->form_data, $field_id ),
							'input_id'    => 'wpforms-panel-field-confirmations-page-' . $field_id,
							'input_class' => 'wpforms-panel-field-confirmations-page',
							'parent'      => 'settings',
							'subsection'  => $field_id,
							'choicesjs'   => [
								'use_ajax'    => true,
								'callback_fn' => 'select_pages',
							],
						]
					);

					wpforms_panel_field(
						'text',
						'confirmations',
						'redirect',
						$settings->form_data,
						esc_html__( 'Confirmation Redirect URL', 'wpforms' ) . ' <span class="required">*</span>',
						[
							'input_id'    => 'wpforms-panel-field-confirmations-redirect-' . $field_id,
							'input_class' => 'wpforms-panel-field-confirmations-redirect',
							'parent'      => 'settings',
							'subsection'  => $field_id,
						]
					);

					wpforms_panel_field(
						'toggle',
						'confirmations',
						'redirect_new_tab',
						$settings->form_data,
						esc_html__( 'Open confirmation in new tab', 'wpforms' ),
						[
							'input_id'    => 'wpforms-panel-field-confirmations-redirect_new_tab-' . $field_id,
							'input_class' => 'wpforms-panel-field-confirmations-redirect_new_tab',
							'parent'      => 'settings',
							'subsection'  => $field_id,
						]
					);

					wpforms_conditional_logic()->builder_block(
						[
							'form'        => $settings->form_data,
							'type'        => 'panel',
							'panel'       => 'confirmations',
							'parent'      => 'settings',
							'subsection'  => $field_id,
							'actions'     => [
								'go'   => esc_html__( 'Use', 'wpforms' ),
								'stop' => esc_html__( 'Don\'t use', 'wpforms' ),
							],
							'action_desc' => esc_html__( 'this confirmation if', 'wpforms' ),
							'reference'   => esc_html__( 'Form confirmations', 'wpforms' ),
						]
					);

					do_action_deprecated(
						'wpforms_form_settings_confirmation',
						[ $settings ],
						'1.4.8 of the WPForms plugin',
						'wpforms_form_settings_confirmations_single_after'
					);

					/**
					 * Fires after each confirmation to add custom fields.
					 *
					 * @since 1.4.8
					 *
					 * @param WPForms_Builder_Panel_Settings $settings Builder panel settings.
					 * @param int                            $field_id Field ID.
					 */
					do_action( 'wpforms_form_settings_confirmations_single_after', $settings, $field_id );
					?>

				</div><!-- /.wpforms-builder-settings-block-content -->

			</div><!-- /.wpforms-builder-settings-block -->

			<?php
		}
	}

	/**
	 * Modify JavaScript `wpforms_settings` properties on the site front end.
	 *
	 * @since 1.4.6
	 *
	 * @param array $strings Array wpforms_setting properties.
	 *
	 * @return array
	 */
	public function frontend_strings( $strings ) {

		// If the user has GDPR enhancements enabled and has disabled UUID,
		// disable the setting, otherwise enable it.
		$strings['uuid_cookie'] = ! wpforms_setting( 'gdpr-disable-uuid' );

		$strings['val_post_max_size'] = wpforms_setting(
			'validation-post_max_size',
			sprintf( /* translators: %1$s - total size of the selected files in megabytes, %2$s - allowed file upload limit in megabytes. */
				esc_html__( 'The total size of the selected files %1$s MB exceeds the allowed limit %2$s MB.', 'wpforms' ),
				'{totalSize}',
				'{maxSize}'
			)
		);

		// Date/time.
		$strings['val_time12h']    = wpforms_setting( 'validation-time12h', esc_html__( 'Please enter time in 12-hour AM/PM format (eg 8:45 AM).', 'wpforms' ) );
		$strings['val_time24h']    = wpforms_setting( 'validation-time24h', esc_html__( 'Please enter time in 24-hour format (eg 22:45).', 'wpforms' ) );
		$strings['val_time_limit'] = wpforms_setting( 'validation-time-limit', esc_html__( 'Please enter time between {minTime} and {maxTime}.', 'wpforms' ) );

		// URL.
		$strings['val_url'] = wpforms_setting( 'validation-url', esc_html__( 'Please enter a valid URL.', 'wpforms' ) );

		// File upload.
		$strings['val_fileextension'] = wpforms_setting( 'validation-fileextension', esc_html__( 'File type is not allowed.', 'wpforms' ) );
		$strings['val_filesize']      = wpforms_setting( 'validation-filesize', esc_html__( 'File exceeds max size allowed. File was not uploaded.', 'wpforms' ) );
		$strings['post_max_size']     = wpforms_size_to_bytes( ini_get( 'post_max_size' ) );

		return $strings;
	}

	/**
	 * Check to see if the Conditional Logic addon is installed, if so notify
	 * the user that it can be removed.
	 *
	 * @since 1.3.8
	 *
	 * @noinspection HtmlUnknownTarget
	 */
	public function conditional_logic_addon_notice() {

		if (
			! defined( 'WPFORMS_DEBUG' ) &&
			file_exists( WP_PLUGIN_DIR . '/wpforms-conditional-logic/wpforms-conditional-logic.php' )
		) {
			$notice = sprintf(
				wp_kses( /* translators: %s - WPForms.com announcement page URL. */
					__( 'Conditional logic functionality is now included in the core WPForms plugin! The WPForms Conditional Logic addon can be removed without affecting your forms. For more details <a href="%s" target="_blank" rel="noopener noreferrer">read our announcement</a>.', 'wpforms' ),
					[
						'a' => [
							'href'   => [],
							'target' => [],
							'rel'    => [],
						],
					]
				),
				'https://wpforms.com/announcing-wpforms-1-3-8/'
			);

			Notice::info( $notice );
		}
	}

	/**
	 * Expired license notification in form notification email footer.
	 *
	 * @since 1.5.0
	 *
	 * @param string $text Footer text.
	 *
	 * @return string
	 * @noinspection HtmlUnknownTarget
	 */
	public function form_notification_footer( $text ) {

		$license = get_option( 'wpforms_license', [] );

		if (
			empty( $license['is_expired'] ) &&
			empty( $license['is_disabled'] ) &&
			empty( $license['is_invalid'] )
		) {
			return $text;
		}

		$notice = sprintf(
			wp_kses(
				/* translators: %s - WPForms.com Account dashboard URL. */
				__( 'Your WPForms license key has expired. In order to continue receiving support and plugin updates you must renew your license key. Please log in to <a href="%s" target="_blank" rel="noopener noreferrer">your WPForms.com account</a> to renew your license.', 'wpforms' ),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			'https://wpforms.com/account/'
		);

		return $notice . '<br><br>' . $text;
	}

	/**
	 * Check if all custom tables exist.
	 *
	 * @since 1.5.9
	 * @deprecated 1.9.0
	 *
	 * @return bool True if all custom tables exist. False if any is missing.
	 */
	public function custom_tables_exist(): bool {

		_deprecated_function( __METHOD__, '1.9.0 of the WPForms plugin', '\WPForms\Helpers\DB::custom_tables_exist()' );

		return DB::custom_tables_exist();
	}

	/**
	 * Create all Pro plugin custom DB tables.
	 *
	 * @since 1.5.9
	 * @since 1.9.0 Removed method argument.
	 * @deprecated 1.9.0
	 */
	public function create_custom_tables() {

		_deprecated_function( __METHOD__, '1.9.0 of the WPForms plugin', 'wpforms()->create_custom_tables()' );

		DB::create_custom_tables();
	}

	/**
	 * Re-create plugin custom tables if they don't exist.
	 *
	 * @since 1.5.9
	 * @deprecated 1.9.0
	 *
	 * @param WPForms_Settings $wpforms_settings WPForms settings object.
	 */
	public function reinstall_custom_tables( WPForms_Settings $wpforms_settings ) {

		_deprecated_function( __METHOD__, '1.9.0 of the WPForms plugin', 'wpforms()->reinstall_custom_tables()' );

		wpforms()->reinstall_custom_tables( $wpforms_settings );
	}

	/**
	 * Allow the WordPress 5.5+ 'auto-updates' feature.
	 *
	 * 1) auto-updates for Lite should work as-is, no changes to the default logic
	 *    for a plugin that is hosted on WP.org
	 * 2) auto-updates for Pro should be controlled using the default WP "Enable auto-updates" link.
	 *    But when it's clicked, we enable it not only for Pro plugin (and updates are retrieved from our API
	 *    as it currently works), but for all of our addons too.
	 * 3) auto-updates for addons cannot be changed per addon. Instead of a link, we should display a plain text
	 *    "Addon auto-updates controlled by WPForms".
	 *    This way, toggling auto-update for Pro will toggle that for ALL addons at once too.
	 *
	 * @since 1.6.4
	 */
	private function allow_wp_auto_update_plugins() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		// If license wasn't found. Is it the Lite version?
		if ( ! wpforms_get_license_type() ) {
			return;
		}

		add_filter( 'plugin_auto_update_setting_html', [ $this, 'auto_update_setting_html' ], 100, 3 );
		add_filter( 'pre_update_site_option_auto_update_plugins', [ $this, 'update_auto_update_plugins_option' ], 100, 4 );
	}

	/**
	 * Filter the HTML of the auto-updates setting for WPForms addons.
	 *
	 * @since 1.6.2.2
	 * @since 1.6.4 Changed the HTML for WPForms addons only.
	 *
	 * @param string $html        The HTML of the plugin's auto-update column content, including
	 *                            toggle auto-update action links and time to next update.
	 * @param string $plugin_file Path to the plugin file relative to the plugins' directory.
	 * @param array  $plugin_data An array of plugin data.
	 *
	 * @return string
	 */
	public function auto_update_setting_html( $html, $plugin_file, $plugin_data ) {

		if ( empty( $plugin_data['Author'] ) ) {
			return $html;
		}

		if ( $plugin_data['Author'] !== 'WPForms' ) {
			return $html;
		}

		if ( 0 !== strpos( $plugin_file, 'wpforms' ) ) {
			return $html;
		}

		$lite_pro_files = [
			'wpforms-lite/wpforms.php',
			'wpforms/wpforms.php',
		];

		if ( in_array( $plugin_file, $lite_pro_files, true ) ) {
			return $html;
		}

		return esc_html__( 'Addon auto-updates controlled by WPForms', 'wpforms' );
	}

	/**
	 * Filter value, which is prepared for `auto_update_plugins` option before it's saved into DB.
	 * We need to include OR exclude all WPForms addons, depends on what status has the main WPForms plugin.
	 *
	 * @since 1.6.2.2
	 * @since 1.6.4 Added dependency from the main WPForms plugin.
	 *
	 * @param mixed  $plugins     New plugins of the network option.
	 * @param mixed  $old_plugins Old plugins of the network option.
	 * @param string $option      Option name.
	 * @param int    $network_id  ID of the network.
	 *
	 * @return array
	 * @noinspection PhpUnusedParameterInspection
	 * @noinspection PhpMissingParamTypeInspection
	 */
	public function update_auto_update_plugins_option( $plugins, $old_plugins, $option, $network_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed

		// No need to filter out our plugins if none were saved.
		if ( empty( $plugins ) ) {
			return $plugins;
		}

		// Protection from malformed data.
		if ( ! is_array( $plugins ) ) {
			return $plugins;
		}

		// Check whether auto-updates for plugins are supported and enabled. If not, return early.
		if (
			! function_exists( 'wp_is_auto_update_enabled_for_type' ) ||
			! wp_is_auto_update_enabled_for_type( 'plugin' )
		) {
			return $plugins;
		}

		// Check whether auto-updates for the main WPForms plugin are enabled.
		// If so, enable it for all WPForms plugins.
		// Otherwise - disable for all WPForms plugins.
		if ( in_array( 'wpforms/wpforms.php', $plugins, true ) ) {
			$new_plugins = array_unique( array_merge( $plugins, $this->get_wpforms_plugins() ) );
		} else {
			$new_plugins = array_diff( $plugins, $this->get_wpforms_plugins() );
		}

		return $new_plugins;
	}

	/**
	 * Add builder strings.
	 *
	 * @since 1.9.5
	 *
	 * @param array  $strings Array of strings.
	 * @param object $form    Form object.
	 *
	 * @return array
	 */
	public function add_builder_strings( $strings, $form ): array { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed

		$status_button_strings = $this->get_status_button_strings();

		return array_merge(
			(array) $strings,
			$status_button_strings
		);
	}

	/**
	 * Get status button strings.
	 *
	 * @since 1.9.5
	 *
	 * @return array
	 */
	private function get_status_button_strings(): array {

		return [
			'active'     => __( 'Active', 'wpforms' ),
			'activate'   => __( 'Activate', 'wpforms' ),
			'inactive'   => __( 'Inactive', 'wpforms' ),
			'deactivate' => __( 'Deactivate', 'wpforms' ),
		];
	}

	/**
	 * Retrieve a collection with WPForms plugins file paths.
	 *
	 * @since 1.6.2.2
	 *
	 * @return array
	 */
	protected function get_wpforms_plugins() {

		$plugins = [];
		$license = wpforms()->obj( 'license' );

		if ( empty( $license ) ) {
			return $plugins;
		}

		$addons_data = $license->get_addons();

		if ( empty( $addons_data ) ) {
			return $plugins;
		}

		$plugins = array_map(
			static function ( $slug ) {

				return "{$slug}/{$slug}.php";
			},
			wp_list_pluck( $addons_data, 'slug' )
		);

		$plugins[] = 'wpforms/wpforms.php';

		return $plugins;
	}
}

return new WPForms_Pro();
