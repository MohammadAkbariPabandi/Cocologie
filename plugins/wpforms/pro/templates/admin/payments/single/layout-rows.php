<?php
/**
 * Single payment entry layout rows template.
 *
 * @since 1.9.3
 *
 * @var array $field Field data.
 * @var array $rows Rows data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$label_hide = $field['label_hide'] ?? false;

?>

<div class="wpforms-payment-entry-layout-block">
	<?php if ( ! $label_hide ) : ?>
		<div class="wpforms-payment-entry-field-name">
			<?php echo esc_html( $field['label'] ); ?>
		</div>
	<?php endif; ?>

	<?php
		foreach ( $rows as $key => $columns ) {
			echo wpforms_render( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'admin/payments/single/row-items',
				[
					'items' => $columns,
					'type'  => $field['type'],
				],
				true
			);
		}
	?>
</div>
