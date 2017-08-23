<?php
/**
 * The plugin page view - the "settings" page of the plugin.
 *
 * @package tpfw
 */
$bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
$size = size_format( $bytes );
$upload_dir = wp_upload_dir();
?>

<div class="wrap tpfw_importer">

    <h1 class="wp-heading-inline tpfw_importer__title">
		<?php echo esc_html__( 'Manually import', 'tp-framework' ); ?>
		<a href="<?php echo admin_url( 'admin.php?import=tpfw_importer' ) ?>" class="hide-if-no-js page-title-action"><?php echo esc_html__( 'Available imports','tp-framework' ) ?></a>
	</h1>

	<?php
	// Display warrning if PHP safe mode is enabled, since we wont be able to change the max_execution_time.
	if ( ini_get( 'safe_mode' ) ) {
		printf(
				esc_html__( '%sWarning: your server is using %sPHP safe mode%s. This means that you might experience server timeout errors.%s', 'tp-framework' ), '<div class="notice  notice-warning  is-dismissible"><p>', '<strong>', '</strong>', '</p></div>'
		);
	}
	?>

    <div class="notice  notice-warning  is-dismissible tpfw_importer__warning">
		<ul>
			<li><span>1</span><?php echo __( 'Backup data before import for safety.', 'tp-framework' ); ?></li>
			<li><span>2</span><?php echo __( 'All the current data wont\'t be lost, all content will be assign to an admin user.', 'tp-framework' ); ?></li>
			<li><span>3</span><?php echo __( 'Before you begin, make sure all the <strong>required plugins</strong> are activated.', 'tp-framework' ); ?></li>
			<li><span>4</span><?php echo __( 'All image data will be fetched and it may also takes a few minutes to import.', 'tp-framework' ); ?></li>
			<li><span>5</span><?php printf( __( 'Maximum size for each file: %s','tp-framework' ), $size ); ?></li>
		</ul>

    </div>



    <!--Upload file to import-->
    <div class="demo-file-upload-container">

        <form enctype="multipart/form-data" id="tpfw_importer-upload-form" method="post" action="<?php echo esc_url( 'admin.php?import=tpfw_importer' ) ?>">
			<?php if ( !empty( $upload_dir['error'] ) ) : ?>

				<div class="error">
					<p><?php echo esc_html__( 'Before you can upload your import file, you will need to fix the following error:', 'tp-framework' ); ?></p>
					<p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
				</div>

			<?php endif; ?>


            <div class="js-importer-progress">
				<div class="progress">
                    <div class="progress-bar"></div>
				</div>
            </div>

            <div class="list-file-upload">
                <div class="demo-file-upload">
					<label for="demo-content-file-upload">
						<h3>
							<?php echo __( 'Content Import', 'tp-framework' ); ?>
						</h3>
						<?php
						echo '<p>' . __( 'Upload your WordPress eXtended RSS (WXR) file and we&#8217;ll import the posts, pages, comments, custom fields, categories, and tags into this site.', 'tp-framework' ) . '</p>';
						echo '<p>' . __( 'Choose a WXR (.xml) file to upload, then click Upload file and import.', 'tp-framework' ) . '</p>';
						?>
						<input id="demo-content-file-upload" class="upload" type="file" name="content_file" accept=".xml">
					</label>
                </div>

                <div class="demo-file-upload">
					<label for="demo-widget-file-upload">
						<h3>
							<?php echo __( 'Widget Import', 'tp-framework' ); ?>
							<span><?php esc_html_e( '(optional)', 'tp-framework' ); ?></span>
						</h3>
						<p>
							<?php echo esc_html__( 'Choose a Widget (.json, .wie) file to upload.', 'tp-framework' ) ?>
						</p>
						<input id="demo-widget-file-upload" class="upload" type="file" name="widget_file"
							   accept=".wie,.json">
					</label>
                </div>

                <div class="demo-file-upload">
					<label for="demo-customizer-file-upload">
						<h3>
							<?php echo __( 'Customizer Import', 'tp-framework' ); ?>
							<span><?php esc_html_e( '(optional)', 'tp-framework' ); ?></span>
						</h3>
						<p>
							<?php echo esc_html__( 'Choose a Customize (.dat) file to upload.', 'tp-framework' ) ?>
						</p>
						<input id="demo-customizer-file-upload" class="upload" type="file" name="customizer_file" accept=".dat">
					</label>
                </div>

            </div>
            <p class="demo-button-container">
                <button class="button  button-hero  button-primary  js-demo-import-data"><?php esc_html_e( 'Update files and import', 'tp-framework' ); ?></button>
            </p>
			<?php wp_nonce_field( 'importer-ajax-verification', 'security' ) ?>
            <input type="hidden"  name="action" value="tpfw_importer"/>
            <input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>"/>

        </form>


        <div class="demo-response  js-demo-ajax-response"></div>
        <div class="js-demo-success">
			<p> <?php echo esc_html__( 'Imported successfully', 'tp-framework' ); ?></p>
        </div>
    </div>


</div>
