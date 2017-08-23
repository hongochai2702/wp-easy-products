<?php
/**
 * The plugin page view - the "settings" page of the plugin.
 *
 * @package tpfw
 */
?>

<div class="wrap tpfw_importer">
	
    <h1 class="wp-heading-inline tpfw_importer__title">
		<?php echo esc_html__( 'Available Imports', 'tp-framework' ); ?>
        <span class="title-count theme-count"><?php echo count( $this->import_files ); ?></span>
		<a href="<?php echo admin_url( 'admin.php?import=tpfw_importer&type=upload' ) ?>" class="hide-if-no-js page-title-action"><?php echo esc_html__( 'Upload import files','tp-framework' ) ?></a>
    </h1>

	<div class="notice  notice-warning  is-dismissible tpfw_importer__warning">
		<ul>
			<li><span>1</span><?php echo __( 'Backup data before import for safety.', 'tp-framework' ); ?></li>
			<li><span>2</span><?php echo __( 'All the current data wont\'t be lost, all content will be assign to an admin user.', 'tp-framework' ); ?></li>
			<li><span>3</span><?php echo __( 'Before you begin, make sure all the <strong>required plugins</strong> are activated.', 'tp-framework' ); ?></li>
			<li><span>4</span><?php echo __( 'All image data will be fetched and it may also takes a few minutes to import.', 'tp-framework' ); ?></li>
		</ul>
    </div>

    <div class="theme-browser rendered">
        <div class="themes wp-clearfix">
			<?php
			if ( !empty( $this->import_files ) ):
				$imported_id = get_option( 'tpfw_imported_id' );

				foreach ( $this->import_files as $index => $import_file ) :

					$slug = 'theme-' . $index;

					// Prepare import item display data.
					$img_src = isset( $import_file['preview_image'] ) ? $import_file['preview_image'] : TPFW_ADDONS_URL . '/importer/assets/img/no-image-preview.png';


					$css_class = '';
					$imported_title = sprintf( '<span>%s</span>', esc_html__( 'Imported: ', 'tp-framework' ) );

					$is_imported = $imported_id !== false && absint( $imported_id ) === $index;

					$import_btn_text = __( 'Import', 'tp-framework' );

					if ( $is_imported ) {
						$css_class = 'active';
						$import_btn_text = __( 'Re-Import', 'tp-framework' );
					}
					?>

					<div class="theme <?php echo esc_attr( $css_class ) ?>" tabindex="<?php echo esc_attr( $index ) ?>"
						 aria-describedby="<?php echo esc_attr( $slug ) ?>-action <?php echo esc_attr( $slug ) ?>-name"
						 data-slug="<?php echo esc_attr( $slug ) ?>">

						<div class="theme-screenshot">

							<a class="more-details" target="_blank" href="<?php echo isset( $import_file['preview_url'] ) ? esc_url( $import_file['preview_url'] ) : '#'; ?>">
								<?php echo esc_html__( 'Live Preview', 'tp-framework' ); ?>
							</a>

                            <div class="placeholder-image">
                                <div class="checkbox-button">
                                    <input type="checkbox" id="<?php echo esc_attr( $slug ) ?>" />
                                    <label for="<?php echo esc_attr( $slug ) ?>">Toggle</label>
                                </div>
                                <span class="checkbox-tittle"><?php echo esc_html__('Use Placeholder Image', 'tp-framework') ?></span>
                            </div>

							<img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $import_file['name'] ) ?>">

							<div class="js-importer-progress">
								<div class="progress">
									<div class="progress-bar"></div>
								</div>
							</div>

						</div>

						<h2 class="theme-name" id="<?php echo esc_attr( $slug ) ?>-name">
							<?php
							echo $imported_title;
							echo esc_html( $import_file['name'] );
							?>
						</h2>

						<div class="theme-actions">

							<a class="button activate button-primary js-button-import" href="#" aria-label="<?php echo esc_attr( $import_file['name'] ) ?>">
								<?php echo esc_html( $import_btn_text ); ?>
							</a>

						</div>
					</div>

				<?php endforeach;
			endif; ?>
			<div class="theme add-new-theme">
				<a href="<?php echo admin_url( 'admin.php?import=tpfw_importer&type=upload' ) ?>">
					<div class="theme-screenshot"><span></span></div>
					<h2 class="theme-name"><?php echo esc_html__( 'Upload import files', 'tp-framework' ) ?></h2>
				</a>
			</div>
        </div>
    </div>
    <div class="theme-overlay"></div>

    <div class="js-demo-ajax-response"></div>
    <div class="js-demo-success">
        <p> <?php echo esc_html__( 'Imported successfully', 'tp-framework' ); ?></p>
    </div>

</div>