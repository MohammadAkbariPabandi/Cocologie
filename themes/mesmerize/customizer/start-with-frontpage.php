
<?php
wp_localize_script(
    'mesmerize_admin_js',
    'mesmerize_page_info',
    array(
        'mesmerize_install_homepage_nonce'   => wp_create_nonce( 'mesmerize_install_homepage_nonce' )
    )
);
?>

<div id="extendthemes_start_with_homepage">
    <div class="extendthemes-container-fluid">
        <div class="extendthemes-row reverse">
            <div class="extendthemes-col">
                <div class="title">
                    <h1 class="">
						<?php
						printf(
							esc_html__(
								'Would you like to install the predesigned %s homepage?',
								'mesmerize'
							),
							apply_filters( 'mesmerize_start_with_front_page_name', 'Mesmerize' )
						);
						?>
                    </h1>

                </div>

                <div>
                    <span>
                        <?php
                        $mesmerize_label = esc_html__(
	                        sprintf(
		                        __( 'Install %s homepage', 'mesmerize' ),
		                        apply_filters( 'mesmerize_start_with_front_page_name', 'Mesmerize' )
	                        )
                        );

                        if ( \Mesmerize\Companion_Plugin::$plugin_state['installed'] ) {
	                        $mesmerize_link = \Mesmerize\Companion_Plugin::get_activate_link();
                        } else {
	                        $mesmerize_link = \Mesmerize\Companion_Plugin::get_install_link();
                        }
                        printf( '<a class="button button-hero button-primary install-frontpage" href="%1$s" install-source="notice">%2$s</a>',
	                        esc_url( $mesmerize_link ),
	                        $mesmerize_label );
                        ?>
                    </span>
                    <span>
                        <button class="button-link maybe-later">
                            <?php esc_html_e(
	                            'Maybe later',
	                            'mesmerize'
                            ); ?>
                        </button>
                    </span>
                </div>
                <div>
                    <p class="description"><?php
						esc_html_e(
							sprintf(
								__( 'This action will install the %s plugin', 'mesmerize' ),
								apply_filters( 'mesmerize_start_with_front_page_plugin', 'Mesmerize Companion' )
							)
						)
						?>
                    </p>
                </div>

            </div>
            <div class="extendthemes-col fit">
                <div class="image-wrapper"
                     style="background-image: url('<?php echo esc_url( get_template_directory_uri() . "/customizer/images/front-page.jpg" ) ?>');">
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery('.mesmerize-start-with-front-page-notice').on('click', '.notice-dismiss', function () {
            jQuery.post(
                ajaxurl,
                {
                    value: 1,
                    action: "companion_disable_popup",
                    companion_disable_popup_wpnonce: '<?php echo wp_create_nonce( "companion_disable_popup" ); ?>'
                }
            )
        });

        jQuery('.maybe-later').on('click', function () {
            var $notice = jQuery(this).closest('.notice.is-dismissible');
            $notice.slideUp('fast', function () {
                $notice.remove();
            });
        });

    </script>
</div>
