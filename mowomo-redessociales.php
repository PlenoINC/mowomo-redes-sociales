<?php
/*
Plugin Name: mowomo Redes Sociales
Plugin URI: https://mowomo.com/
Description: Light and simple plugin for allowing the readers of your blog to share your entries on their social networks. If you only need to offer the possibility to your readers of sharing your blog entries... Why getting complicated?
Author: mowomo
Author URI: https://mowomo.com/sobre-mowomo
Text Domain: mowomo-redes-sociales
Domain Path: /lenguages/
Version: 1.2.2
License: GPLv2 or later.
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Pa' fuera
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * mowomo-redessociales
 *
 * @since      1.0.0
 */

define( 'MWMRRSS_PLUGIN', __FILE__ );
define( 'MWMRRSS_PLUGIN_DIR', untrailingslashit( dirname( MWMRRSS_PLUGIN ) ) );

require_once MWMRRSS_PLUGIN_DIR . '/configuracion.php';

/**
 * mowomo-redessociales
 *
 * @since      1.0.0
 */
add_action( 'wp_enqueue_scripts', 'mwm_rrss_js');
function mwm_rrss_js() {

    wp_enqueue_script( 'mwm_js', plugin_dir_url( __FILE__ ) . '/js/mwm_rrss_scripts.js' , array(), '1.0.0', true);

}

/**
 * mowomo-redessociales
 *
 * @since      1.0.0
 */
add_action( 'wp_enqueue_scripts', 'mwm_rrss_css');
function mwm_rrss_css() {

    wp_enqueue_style( 'mwm_rrss_style', plugin_dir_url( __FILE__ ) . 'mwm_rrss_style.css', array(), '1.0.0' );

}

function contenido_redes_sociales($red_social_activa){

      switch ($red_social_activa) {
          case 'twitter':
            return '<a class="mwm_rrss mwm_twitter" onclick="compartirRrss(\'https://twitter.com/intent/tweet?text='. get_the_title() .' '. get_permalink() .' vía @'. get_option('mwm_rrss_twitter') .'\',\'_blank\');">'. esc_html( __( "Twitter", "mwm_rrss" ) ) .'</a>';
            break;
          case 'facebook':
            return '<a class="mwm_rrss mwm_facebook" onclick="compartirRrss(\'https://www.facebook.com/sharer/sharer.php?u='. get_permalink() .'\',\'_blank\');">'. esc_html( __( "Facebook", "mwm_rrss" ) ) .'</a>';
            break;
          case 'pinterest':
            return '<a class="mwm_rrss mwm_pinterest" onclick="compartirRrss(\'http://pinterest.com/pin/create/button/?url='. get_permalink() .'&media='.get_the_post_thumbnail_url().'&description='.get_the_title().'\',\'_blank\');">'. esc_html( __( "Pinterest", "mwm_rrss" ) ) .'</a>';
            break;
          case 'whatsapp':
            return '<a class="mwm_rrss mwm_whatsapp" href="whatsapp://send?text='. get_the_title() .' – '.get_permalink().'" data-action="share/whatsapp/share" >'. esc_html( __( "WhatsApp", "mwm_rrss" ) ) .'</a>';
            break;
          case 'linkedin':
            return '<a class="mwm_rrss mwm_linkedin" onclick="compartirRrss(\'https://www.linkedin.com/shareArticle?mini=true&url=' . get_permalink() . '&title=' . get_the_title() . '&source=' . get_the_post_thumbnail_url() . '\',\'_blank\');">'. esc_html( __( "Linkedin", "mwm_rrss" ) ) .'</a>';
            break;
          default:
          return "";
            break;
        }
}
/**
 * mowomo-redessociales
 *
 * @since      1.0.0
 */
function mwm_rrss_contenido() {
    //Saber que opciones están checkeadas
    $redes_sociales_activas   = get_option('mwm_rrss_actives');
    $contenido = '<div class="mwm_rrss_contenedor">';

    if (is_array($redes_sociales_activas) || is_object($redes_sociales_activas)) {
      foreach ($redes_sociales_activas as $red_social_activa) {
        $contenido .= contenido_redes_sociales($red_social_activa);
      }
    } else {
      $contenido .= $red_social_activas;
    }
    $contenido .= '</div>';

    return $contenido;
}

/**
 * mowomo-redessociales
 *
 * @since      1.0.0
 */
function mwm_metas_redessociales() {

    global $post;

    // Twitter specific
    echo '<meta name="twitter:card" 		content="summary_large_image" />';

    // Meta tags for Open Graph
    echo '<meta property="og:description" 	content="' . esc_attr( mwm_rrss_get_descripcion_post() ) . '" />';
    echo '<meta property="og:type"			content="article" />';
    echo '<meta property="og:image" 		content="' . esc_attr( mwm_rrss_get_imagen_destacada_post() ) . '" />';

    do_action( 'mwm_metas_redessociales' );

}
add_action( 'wp_head', 'mwm_metas_redessociales' );

/**
 * mowomo-redessociales
 *
 * @since      1.0.0
 */
function mwm_before_after($content) {

  if(is_single()) {
      $contenido = mwm_rrss_contenido();
      $posicion = get_option('mwm_rrss_posicion');
      switch ($posicion){
          case '':
          case '0':
              $fullcontent = $content;
              break;
          case '1':
              $fullcontent = $contenido . $content;
              break;
          case '2':
              $fullcontent = $content . $contenido;
              break;
          case '3':
              $fullcontent = $contenido . $content . $contenido;
              break;
      }
      return $fullcontent;
  } else {
      return $content;
  }

}
add_filter('the_content', 'mwm_before_after');