<?php
/**
 * iThemes Exchange Easy Canadian Sales Taxes Add-on
 * @package exchange-addon-easy-canadian-sales-taxes
 * @since 1.0.0
*/

/**
 * Call back for settings page
 *
 * This is set in options array when registering the add-on and called from it_exchange_enable_addon()
 *
 * @since 1.0.0
 * @return void
*/
function it_exchange_easy_canadian_sales_taxes_settings_callback() {
	$IT_Exchange_Easy_Canadian_Sales_Taxes_Add_On = new IT_Exchange_Easy_Canadian_Sales_Taxes_Add_On();
	$IT_Exchange_Easy_Canadian_Sales_Taxes_Add_On->print_settings_page();
}

/**
 * Sets the default options for ccanadiantomer pricing settings
 *
 * @since 1.0.0
 * @return array settings
*/
function it_exchange_easy_canadian_sales_taxes_default_settings( $defaults ) {
	$defaults = array(
		'tax-rates' => array(
			array(
				'province' => 'AB', // Alberta
				'type'     => 'GST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'BC', // British Columbia
				'type'     => 'GST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'BC', // British Columbia
				'type'     => 'PST',
				'rate'     => '7',
				'shipping' => false,
			),
			array(
				'province' => 'MB', // Manitoba
				'type'     => 'GST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'MB', // Manitoba
				'type'     => 'PST',
				'rate'     => '8',
				'shipping' => false,
			),
			array(
				'province' => 'NB', // New Brunswick
				'type'     => 'HST',
				'rate'     => '13',
				'shipping' => false,
			),
			array(
				'province' => 'NF', // Newfoundland
				'type'     => 'HST',
				'rate'     => '13',
				'shipping' => false,
			),
			array(
				'province' => 'NT', // Northwest Territories
				'type'     => 'GST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'NS', // Nova Scotia
				'type'     => 'HST',
				'rate'     => '15',
				'shipping' => false,
			),
			array(
				'province' => 'NU', // Nunavut
				'type'     => 'GST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'ON', // Ontario
				'type'     => 'HST',
				'rate'     => '13',
				'shipping' => false,
			),
			array(
				'province' => 'PE', // Prince Edward Island
				'type'     => 'HST',
				'rate'     => '14',
				'shipping' => false,
			),
			array(
				'province' => 'QC', // Quebec
				'type'     => 'GST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'QC', // Quebec
				'type'     => 'PST',
				'rate'     => '9.975',
				'shipping' => false,
			),
			array(
				'province' => 'SK', // Saskatchewan
				'type'     => 'GST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'SK', // Saskatchewan
				'type'     => 'PST',
				'rate'     => '5',
				'shipping' => false,
			),
			array(
				'province' => 'YT', // Yukon Territory
				'type'     => 'PST',
				'rate'     => '5',
				'shipping' => false,
			),
		),
	);
	return $defaults;
}
add_filter( 'it_storage_get_defaults_exchange_addon_easy_canadian_sales_taxes', 'it_exchange_easy_canadian_sales_taxes_default_settings' );

class IT_Exchange_Easy_Canadian_Sales_Taxes_Add_On {

	/**
	 * @var boolean $_is_admin true or false
	 * @since 1.0.0
	*/
	var $_is_admin;

	/**
	 * @var string $_current_page Current $_GET['page'] value
	 * @since 1.0.0
	*/
	var $_current_page;

	/**
	 * @var string $_current_add_on Current $_GET['add-on-settings'] value
	 * @since 1.0.0
	*/
	var $_current_add_on;

	/**
	 * @var string $statcanadian_message will be displayed if not empty
	 * @since 1.0.0
	*/
	var $statcanadian_message;

	/**
	 * @var string $error_message will be displayed if not empty
	 * @since 1.0.0
	*/
	var $error_message;

	/**
 	 * Class constructor
	 *
	 * Sets up the class.
	 * @since 1.0.0
	 * @return void
	*/
	function IT_Exchange_Easy_Canadian_Sales_Taxes_Add_On() {
		$this->_is_admin       = is_admin();
		$this->_current_page   = empty( $_GET['page'] ) ? false : $_GET['page'];
		$this->_current_add_on = empty( $_GET['add-on-settings'] ) ? false : $_GET['add-on-settings'];

		if ( ! empty( $_POST ) && $this->_is_admin && 'it-exchange-addons' == $this->_current_page && 'easy-canadian-sales-taxes' == $this->_current_add_on ) {
			add_action( 'it_exchange_save_add_on_settings_easy_canadian_sales_taxes', array( $this, 'save_settings' ) );
			do_action( 'it_exchange_save_add_on_settings_easy_canadian_sales_taxes' );
		}
	}

	function print_settings_page() {
		$settings = it_exchange_get_option( 'addon_easy_canadian_sales_taxes', true );
	
		$form_values  = empty( $this->error_message ) ? $settings : ITForm::get_post_data();
		$form_options = array(
			'id'      => apply_filters( 'it_exchange_add_on_easy_canadian_sales_taxes', 'it-exchange-add-on-easy-canadian-sales-taxes-settings' ),
			'enctype' => apply_filters( 'it_exchange_add_on_easy_canadian_sales_taxes_settings_form_enctype', false ),
			'action'  => 'admin.php?page=it-exchange-addons&add-on-settings=easy-canadian-sales-taxes',
		);
		$form         = new ITForm( $form_values, array( 'prefix' => 'it-exchange-add-on-easy-canadian-sales-taxes' ) );

		if ( ! empty ( $this->statcanadian_message ) )
			ITUtility::show_statcanadian_message( $this->statcanadian_message );
		if ( ! empty( $this->error_message ) )
			ITUtility::show_error_message( $this->error_message );

		?>
		<div class="wrap">
			<?php screen_icon( 'it-exchange' ); ?>
			<h2><?php _e( 'Easy Canadian Sales Taxes Settings', 'LION' ); ?></h2>

			<?php do_action( 'it_exchange_easy_canadian_sales_taxes_settings_page_top' ); ?>
			<?php do_action( 'it_exchange_addon_settings_page_top' ); ?>

			<?php $form->start_form( $form_options, 'it-exchange-easy-canadian-sales-taxes-settings' ); ?>
				<?php do_action( 'it_exchange_easy_canadian_sales_taxes_settings_form_top' ); ?>
				<?php $this->get_easy_canadian_sales_taxes_form_table( $form, $form_values ); ?>
				<?php do_action( 'it_exchange_easy_canadian_sales_taxes_settings_form_bottom' ); ?>
				<p class="submit">
					<?php $form->add_submit( 'submit', array( 'value' => __( 'Save Changes', 'LION' ), 'class' => 'button button-primary button-large' ) ); ?>
				</p>
			<?php $form->end_form(); ?>
			<?php do_action( 'it_exchange_easy_canadian_sales_taxes_settings_page_bottom' ); ?>
			<?php do_action( 'it_exchange_addon_settings_page_bottom' ); ?>
		</div>
		<?php
	}

	function get_easy_canadian_sales_taxes_form_table( $form, $settings = array() ) {
		if ( !empty( $settings ) )
			foreach ( $settings as $key => $var )
				$form->set_option( $key, $var );
		?>
		
        <div class="it-exchange-addon-settings it-exchange-easy-canadian-sales-taxes-addon-settings">
            <h4>
            	<?php _e( 'Current Tax Rates and Settings', 'LION' ) ?> 
            </h4>
			<div id="canadian-tax-rate-table">
			<?php
			$headings = array(
				__( 'Province', 'LION' ), __( 'Tax Type', 'LION' ), __( 'Tax Rate', 'LION' ), __( 'Apply to Shipping?', 'LION' )
			);
			?>
			<div class="heading-row block-row">
				<?php $column = 0; ?>
				<?php foreach ( (array) $headings as $heading ) : ?>
				<?php $column++ ?>
				<div class="heading-column block-column block-column-<?php echo $column; ?>">
				<p class="heading"><?php echo $heading; ?></p>
				</div>
				<?php endforeach; ?>
				<div class="heading-column block-column block-column-delete"></div>
			</div>
			<?php
			$row = 0;
			//ITDebug::print_r( $settings['tax-rates'] );
			foreach( $settings['tax-rates'] as $rate ) {
				echo it_exchange_easy_canadian_sales_taxes_get_tax_row_settings( $row, $rate );
				$row++;
			}
			?>
			</div>
			<script type="text/javascript" charset="utf-8">
	            var it_exchange_easy_canadian_sales_taxes_addon_iteration = <?php echo $row; ?>;
	        </script>

			<p class="add-new">
				<?php $form->add_button( 'new-tax-rate', array( 'value' => __( 'Add New Tax Rate', 'LION' ), 'class' => 'button button-secondary button-large' ) ); ?>
			</p>
			
			<p class="reset">
				<?php $form->add_button( 'reset-tax-rates', array( 'value' => __( 'Reset All Tax Rates', 'LION' ), 'class' => 'button button-secondary button-large' ) ); ?>
			</p>

            
		</div>
		<?php
	}

	/**
	 * Save settings
	 *
	 * @since 1.0.0
	 * @return void
	*/
    function save_settings() {
    	global $new_values; //We set this as global here to modify it in the error check
    	
        $defaults = it_exchange_get_option( 'addon_easy_canadian_sales_taxes' );
        $new_values = wp_parse_args( ITForm::get_post_data(), $defaults );
                
        // Check nonce
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'it-exchange-easy-canadian-sales-taxes-settings' ) ) {
            $this->error_message = __( 'Error. Please try again', 'LION' );
            return;
        }

        $errors = apply_filters( 'it_exchange_add_on_easy_canadian_sales_taxes_validate_settings', $this->get_form_errors( $new_values ), $new_values );
        if ( ! $errors && it_exchange_save_option( 'addon_easy_canadian_sales_taxes', $new_values ) ) {
            ITUtility::show_status_message( __( 'Settings saved.', 'LION' ) );
        } else if ( $errors ) {
            $errors = implode( '<br />', $errors );
            $this->error_message = $errors;
        } else {
            $this->status_message = __( 'Settings not saved.', 'LION' );
        }
    }

    /**
     * Validates for values
     *
     * Returns string of errors if anything is invalid
     *
     * @since 0.1.0
     * @return void
    */
    public function get_form_errors( $values ) {
    	global $new_values;

        $errors = array();

        return $errors;
    }
}