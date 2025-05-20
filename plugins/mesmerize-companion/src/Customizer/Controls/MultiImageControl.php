<?php

namespace Mesmerize\Customizer\Controls;

class MultiImageControl extends \Mesmerize\Customizer\BaseControl {

	public function init() {
		$this->cpData['min']   = isset( $this->cpData['min'] ) ? $this->cpData['min'] : 2;
		$this->cpData['limit'] = isset( $this->cpData['max'] ) ? $this->cpData['max'] : 2;
	}


	public function enqueue() {
		 $jsUrl = $this->companion()->assetsRootURL() . '/js/customizer';
		wp_enqueue_script( 'cp-multi-image-control', $jsUrl . '/multi-image-control.js', array( 'cp-customizer-base' ) );
		wp_localize_script(
			'cp-multi-image-control',
			'cpMultiImageTexts',
			array(
				'addImage'    => __( 'Add New Image', 'cloudpress-companion' ),
				'deleteTitle' => __( 'Delete image from slideshow', 'cloudpress-companion' ),
				'changeTitle' => __( 'changeImage', 'cloudpress-companion' ),
			)
		);
	}


	public function render() {
		$id      = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class   = 'list ';
		$options = 'data-min="' . esc_attr( $this->cpData['min'] ) . '" data-max="' . esc_attr( $this->cpData['limit'] ) . '"'; ?>

		<li data-type="cp-multi-image-manager" id="<?php echo esc_attr( $id ); ?>" <?php $this->link(); ?> <?php echo $options; ?> class="<?php echo esc_attr( $class ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php
			endif;
			if ( ! empty( $this->description ) ) :
				?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<div>
				<?php $this->render_content(); ?>
			</div>
			<div class="add-new-container">
				<button type="button" class="button upload-button control-focus">Add New Image</button>
			</div>
		</li>
		<?php

	}

	public function render_content() {
		$value = $this->value();

		if ( count( $value ) ) {
			foreach ( $value as $item ) {
				$this->renderItem( $item );
			}
		} else {
			for ( $i = 0; $i < $this->cpData['min']; $i++ ) {
				$this->renderItem( '' );
			}
		}
	}

	private function renderItem( $item ) {
		$field_id = uniqid( $this->id );
		$value    = $this->value();
		?>
		<div class="cp-multi-image-item attachment-media-view attachment-media-view-image ">
			<div class="thumbnail thumbnail-image">
				<img id="<?php echo esc_attr( $field_id ); ?>-thumb" class="attachment-thumb" src="<?php echo esc_attr( $item ); ?>" draggable="false" alt="">
			</div>
			<div class="actions">
				<input type="hidden" value="<?php echo esc_attr( $item ); ?>" id="<?php echo esc_attr( $field_id ); ?>" />
				<div class="actions">
					<span title="Change Image" onClick='CP_Customizer.openMediaBrowser("image", jQuery("#<?php echo esc_attr( $field_id ); ?>"))' class="open-right section-icon"></span>
					<?php if ( count( $value ) > $this->cpData['min'] ) : ?>
						<span title="Delete image from slideshow" class="item-remove"></span>
					<?php endif; ?>
				</div>
				<script>
					jQuery("#<?php echo esc_attr( $field_id ); ?>").change(function() {
						jQuery('#<?php echo esc_attr( $field_id ); ?>-thumb').attr('src', this.value);
					});
				</script>
			</div>
		<?php

	}
}
