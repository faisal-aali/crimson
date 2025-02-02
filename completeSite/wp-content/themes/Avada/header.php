<?php
/**
 * Header template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<!DOCTYPE html>
<?php global $woocommerce; ?>
<html class="<?php echo ( Avada()->settings->get( 'smooth_scrolling' ) ) ? 'no-overflow-y' : ''; ?>" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php Avada()->head->the_viewport(); ?>

	<?php wp_head(); ?>

	<?php $object_id = get_queried_object_id(); ?>
	<?php $c_page_id = Avada()->fusion_library->get_page_id(); ?>

	<script type="text/javascript">
		var doc = document.documentElement;
		doc.setAttribute('data-useragent', navigator.userAgent);
	</script>

	<?php
	/**
	 *
	 * The settings below are not sanitized.
	 * In order to be able to take advantage of this,
	 * a user would have to gain access to the database
	 * in which case this is the least on your worries.
	 */
	// @codingStandardsIgnoreLine
	echo Avada()->settings->get( 'google_analytics' );
	// @codingStandardsIgnoreLine
	echo Avada()->settings->get( 'space_head' );
	?>
<style>
.woocommerce-products-header{display:none !important;}

.woocommerce span.soldout, .woocommerce-page span.soldout {

    z-index: 999999999999;

}
.products .soldout {
    display: none;
}
.mobile_qty{display:none;}
@media (max-width:480px)  { /* smartphones, Android phones, landscape iPhone */ 
.mobile_qty{
display:block;
}
.mobile_qty .minus,.mobile_qty .plus {display:none;}
}
.mobile_qty{text-align:center;}


.tips.billing-same-as-shipping{
display:block !important;
}
</style>
<script>
var $ = $ ? $ : jQuery;

if ($(window).width() < 480) { 
	setTimeout(function(e){$('td.product-quantity').html(" ")},1000);
}
var path = window.location.pathname;
var page = path.split("/")[1];

if(page=="cart"){
setTimeout(function(e){
console.log($('.remove'));
$('.remove').on('click',function(e){
    setTimeout(function(e){
		var total = $('.order-total .woocommerce-Price-amount.amount').text().replace(",","").replace("₨","");
	if(total<=75000){
		$('.woocommerce-error').hide()
	}
    },2000);
});
 },2000);
}
</script>
</head>

<?php
$wrapper_class = ( is_page_template( 'blank.php' ) ) ? 'wrapper_blank' : '';

if ( 'modern' === Avada()->settings->get( 'mobile_menu_design' ) ) {
	$mobile_logo_pos = strtolower( Avada()->settings->get( 'logo_alignment' ) );
	if ( 'center' === strtolower( Avada()->settings->get( 'logo_alignment' ) ) ) {
		$mobile_logo_pos = 'left';
	}
}

?>
<body <?php body_class(); ?>>
	<?php do_action( 'avada_before_body_content' );

	$boxed_side_header_right = false;
	$page_bg_layout = ( $c_page_id ) ? get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) : 'default';
	?>
	<?php if ( ( ( 'Boxed' === Avada()->settings->get( 'layout' ) && ( 'default' === $page_bg_layout || '' == $page_bg_layout ) ) || 'boxed' === $page_bg_layout ) && 'Top' != Avada()->settings->get( 'header_position' ) ) : ?>
		<?php if ( Avada()->settings->get( 'slidingbar_widgets' ) && ! is_page_template( 'blank.php' ) && ( 'Right' == Avada()->settings->get( 'header_position' ) || 'Left' == Avada()->settings->get( 'header_position' ) ) ) : ?>
			<?php get_template_part( 'slidingbar' ); ?>
			<?php $boxed_side_header_right = true; ?>
		<?php endif; ?>
		<div id="boxed-wrapper">
	<?php endif; ?>
	<?php if ( ( ( 'Boxed' === Avada()->settings->get( 'layout' ) && 'default' === $page_bg_layout ) || 'boxed' === $page_bg_layout ) && 'framed' === Avada()->settings->get( 'scroll_offset' ) && 0 !== intval( Avada()->settings->get( 'margin_offset', 'top' ) ) ) : ?>
		<div class="fusion-sides-frame"></div>
	<?php endif; ?>
	<div id="wrapper" class="<?php echo esc_attr( $wrapper_class ); ?>">
		<div id="home" style="position:relative;top:1px;"></div>
		<?php if ( Avada()->settings->get( 'slidingbar_widgets' ) && ! is_page_template( 'blank.php' ) && ! $boxed_side_header_right ) : ?>
			<?php get_template_part( 'slidingbar' ); ?>
		<?php endif; ?>
		<?php if ( false !== strpos( Avada()->settings->get( 'footer_special_effects' ), 'footer_sticky' ) ) : ?>
			<div class="above-footer-wrapper">
		<?php endif; ?>

		<?php avada_header_template( 'Below' ); ?>
		<?php if ( 'Left' === Avada()->settings->get( 'header_position' ) || 'Right' === Avada()->settings->get( 'header_position' ) ) : ?>
			<?php avada_side_header(); ?>
		<?php endif; ?>

		<div id="sliders-container">
			<?php
			if ( is_search() ) {
				$slider_page_id = '';
			} else {
				$slider_page_id = '';
				if ( ( ! is_home() && ! is_front_page() && ! is_archive() && isset( $object_id ) ) || ( ! is_home() && is_front_page() && isset( $object_id ) ) ) {
					$slider_page_id = $object_id;
				}
				if ( is_home() && ! is_front_page() ) {
					$slider_page_id = get_option( 'page_for_posts' );
				}
				if ( class_exists( 'WooCommerce' ) && is_shop() ) {
					$slider_page_id = get_option( 'woocommerce_shop_page_id' );
				}

				if ( ( 'publish' === get_post_status( $slider_page_id ) && ! post_password_required() ) || ( current_user_can( 'read_private_pages' ) && in_array( get_post_status( $slider_page_id ), array( 'private', 'draft', 'pending' ) ) ) ) {
					avada_slider( $slider_page_id );
				}
			} ?>
		</div>
		<?php
		$slider_fallback = get_post_meta( $slider_page_id, 'pyre_fallback', true );
		?>
		<?php if ( $slider_fallback ) : ?>
			<div id="fallback-slide">
				<img src="<?php echo esc_url_raw( $slider_fallback ); ?>" alt="" />
			</div>
		<?php endif; ?>
		<?php avada_header_template( 'Above' ); ?>

		<?php if ( has_action( 'avada_override_current_page_title_bar' ) ) : ?>
			<?php do_action( 'avada_override_current_page_title_bar', $c_page_id ); ?>
		<?php else : ?>
			<?php avada_current_page_title_bar( $c_page_id ); ?>
		<?php endif; ?>

		<?php if ( is_page_template( 'contact.php' ) && Avada()->settings->get( 'recaptcha_public' ) && Avada()->settings->get( 'recaptcha_private' ) ) : ?>
			<script type="text/javascript">var RecaptchaOptions = { theme : '<?php echo esc_attr( Avada()->settings->get( 'recaptcha_color_scheme' ) ); ?>' };</script>
		<?php endif; ?>

		<?php if ( is_page_template( 'contact.php' ) && Avada()->settings->get( 'gmap_address' ) && Avada()->settings->get( 'status_gmap' ) ) : ?>
			<?php
			$map_popup             = ( ! Avada()->settings->get( 'map_popup' ) )        ? 'yes' : 'no';
			$map_scrollwheel       = ( Avada()->settings->get( 'map_scrollwheel' ) )    ? 'yes' : 'no';
			$map_scale             = ( Avada()->settings->get( 'map_scale' ) )          ? 'yes' : 'no';
			$map_zoomcontrol       = ( Avada()->settings->get( 'map_zoomcontrol' ) )    ? 'yes' : 'no';
			$address_pin           = ( Avada()->settings->get( 'map_pin' ) )            ? 'yes' : 'no';
			$address_pin_animation = ( Avada()->settings->get( 'gmap_pin_animation' ) ) ? 'yes' : 'no';
			?>
			<div id="fusion-gmap-container">
				<?php
				// @codingStandardsIgnoreLine
				echo Avada()->google_map->render_map( array(
					'address'                  => wp_kses_post( Avada()->settings->get( 'gmap_address' ) ),
					'type'                     => esc_attr( Avada()->settings->get( 'gmap_type' ) ),
					'address_pin'              => esc_attr( $address_pin ),
					'animation'                => esc_attr( $address_pin_animation ),
					'map_style'                => esc_attr( Avada()->settings->get( 'map_styling' ) ),
					'overlay_color'            => esc_attr( Avada()->settings->get( 'map_overlay_color' ) ),
					'infobox'                  => esc_attr( Avada()->settings->get( 'map_infobox_styling' ) ),
					'infobox_background_color' => esc_attr( Avada()->settings->get( 'map_infobox_bg_color' ) ),
					'infobox_text_color'       => esc_attr( Avada()->settings->get( 'map_infobox_text_color' ) ),
					'infobox_content'          => wp_kses_post( htmlentities( Avada()->settings->get( 'map_infobox_content' ) ) ),
					'icon'                     => esc_attr( Avada()->settings->get( 'map_custom_marker_icon' ) ),
					'width'                    => esc_attr( Avada()->settings->get( 'gmap_dimensions', 'width' ) ),
					'height'                   => esc_attr( Avada()->settings->get( 'gmap_dimensions', 'height' ) ),
					'zoom'                     => esc_attr( Avada()->settings->get( 'map_zoom_level' ) ),
					'scrollwheel'              => esc_attr( $map_scrollwheel ),
					'scale'                    => esc_attr( $map_scale ),
					'zoom_pancontrol'          => esc_attr( $map_zoomcontrol ),
					'popup'                    => esc_attr( $map_popup ),
				) );
				?>
			</div>
		<?php endif; ?>

		<?php if ( is_page_template( 'contact-2.php' ) && Avada()->settings->get( 'gmap_address' ) && Avada()->settings->get( 'status_gmap' ) ) : ?>
			<?php
			$map_popup             = ( ! Avada()->settings->get( 'map_popup' ) )          ? 'yes' : 'no';
			$map_scrollwheel       = ( Avada()->settings->get( 'map_scrollwheel' ) )    ? 'yes' : 'no';
			$map_scale             = ( Avada()->settings->get( 'map_scale' ) )          ? 'yes' : 'no';
			$map_zoomcontrol       = ( Avada()->settings->get( 'map_zoomcontrol' ) )    ? 'yes' : 'no';
			$address_pin_animation = ( Avada()->settings->get( 'gmap_pin_animation' ) ) ? 'yes' : 'no';
			?>
			<div id="fusion-gmap-container">
				<?php // @codingStandardsIgnoreLine
				echo Avada()->google_map->render_map( array(
					'address'                  => wp_kses_post( Avada()->settings->get( 'gmap_address' ) ),
					'type'                     => esc_attr( Avada()->settings->get( 'gmap_type' ) ),
					'map_style'                => esc_attr( Avada()->settings->get( 'map_styling' ) ),
					'animation'                => esc_attr( $address_pin_animation ),
					'overlay_color'            => esc_attr( Avada()->settings->get( 'map_overlay_color' ) ),
					'infobox'                  => esc_attr( Avada()->settings->get( 'map_infobox_styling' ) ),
					'infobox_background_color' => esc_attr( Avada()->settings->get( 'map_infobox_bg_color' ) ),
					'infobox_text_color'       => esc_attr( Avada()->settings->get( 'map_infobox_text_color' ) ),
					'infobox_content'          => wp_kses_post( htmlentities( Avada()->settings->get( 'map_infobox_content' ) ) ),
					'icon'                     => esc_attr( Avada()->settings->get( 'map_custom_marker_icon' ) ),
					'width'                    => esc_attr( Avada()->settings->get( 'gmap_dimensions', 'width' ) ),
					'height'                   => esc_attr( Avada()->settings->get( 'gmap_dimensions', 'height' ) ),
					'zoom'                     => esc_attr( Avada()->settings->get( 'map_zoom_level' ) ),
					'scrollwheel'              => esc_attr( $map_scrollwheel ),
					'scale'                    => esc_attr( $map_scale ),
					'zoom_pancontrol'          => esc_attr( $map_zoomcontrol ),
					'popup'                    => esc_attr( $map_popup ),
				) );
				?>
			</div>
		<?php endif; ?>
		<?php
		$main_css   = '';
		$row_css    = '';
		$main_class = '';

		if ( Avada()->layout->is_hundred_percent_template() ) {
			$main_css = 'padding-left:0px;padding-right:0px;';
			$hundredp_padding = get_post_meta( $c_page_id, 'pyre_hundredp_padding', true );
			if ( Avada()->settings->get( 'hundredp_padding' ) && ! $hundredp_padding ) {
				$main_css = 'padding-left:' . Avada()->settings->get( 'hundredp_padding' ) . ';padding-right:' . Avada()->settings->get( 'hundredp_padding' );
			}
			if ( $hundredp_padding ) {
				$main_css = 'padding-left:' . $hundredp_padding . ';padding-right:' . $hundredp_padding;
			}
			$row_css    = 'max-width:100%;';
			$main_class = 'width-100';
		}
		do_action( 'avada_before_main_container' );
		?>
		<div id="main" role="main" class="clearfix <?php echo esc_attr( $main_class ); ?>" style="<?php echo esc_attr( $main_css ); ?>">
			<div class="fusion-row" style="<?php echo esc_attr( $row_css ); ?>">
