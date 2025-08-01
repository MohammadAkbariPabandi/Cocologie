<?php
/**
 * Single entry layout field rows template.
 *
 * @since 1.9.0
 *
 * @var array                  $field          Field data.
 * @var array                  $form_data      Form data and settings.
 * @var WPForms_Entries_Single $entries_single Single entry object.
 * @var bool                   $is_hidden_by_cl Is the field hidden by conditional logic.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPForms\Pro\Forms\Fields\Layout\Helpers as LayoutHelpers;

$rows = isset( $field['columns'] ) && is_array( $field['columns'] ) ? LayoutHelpers::get_row_data( $field ) : [];

if ( empty( $rows ) ) {
	return;
}

$classes = [ 'wpforms-field-layout-row' ];

if ( $is_hidden_by_cl ) {
	$classes[] = 'wpforms-conditional-hidden';
}

if ( LayoutHelpers::is_layout_empty( $field ) ) {
	$classes[] = 'empty';

	if ( empty( $entries_single->entry_view_settings['fields']['show_empty_fields']['value'] ) ) {
		$classes[] = 'wpforms-hide';
	}
}

$context = [ 'layout-row' => true ];
?>

<div class="<?php echo wpforms_sanitize_classes( $classes, true ); ?>">
	<?php
	echo wpforms_render( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'admin/entries/single-entry/block-header',
		[
			'field'          => $field,
			'form_data'      => $form_data,
			'entries_single' => $entries_single,
		],
		true
	);
	?>

	<div class="wpforms-field-layout-rows">
		<?php foreach ( $rows as $row ) : ?>
			<div class="wpforms-layout-row">
				<?php foreach ( $row as $data ) : ?>
					<?php $width = wpforms_get_column_width( $data ); ?>
					<div class="wpforms-entry-field-layout-inner wpforms-field-layout-column" style="--field-layout-column-width: <?php echo esc_attr( $width ); ?>%">
						<?php
						if ( $data['field'] ) {
							$entries_single->print_field( $data['field'], $form_data, $context );
						}
						?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>

