<?php
/*
Plugin Name: Filter WooCommerce Featured Products
Plugin URI: http://eltondev.com.br
Description: Filtrar Woocommerce produtos destacados e não destacados
Version: 1.0
Author: EltonDEV
Author URI: http://eltondev.com.br
*/

add_action( 'restrict_manage_posts', 'featured_product_search_filter_manager' );
/**
 * Primeiro cria o dropdown
 * 
 * @author EltonDEV
 * 
 * @return void
 */
function featured_product_search_filter_manager(){
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    
	//adiciona o filtro para o tipo que você quiser
    if ('product' == $type){
      
	  //mude isso para a lista de valores que você quer mostrar
        $values = array(
            'Mostrar somente em destaque' => 'Yes', 
            'Mostrar somente fora de destaque' => 'No',
        );
        ?>
        <select name="Featured">
        <option value=""><?php _e('Mostrar em destaque e fora de destaque', 'woocommerce_filter'); ?></option>
        <?php
            $current_v = isset($_GET['Featured'])? $_GET['Featured']:'';
            foreach ($values as $label => $value) {
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $value,
                        $value == $current_v? ' selected="selected"':'',
                        $label
                    );
                }
        ?>
        </select>
        <?php
    }
}
add_filter( 'parse_query', 'woocommerce_filter_posts_filter' );

/**
 *
 *submeter filtro 
 * 
 * @author EltonDEV
 * @param  (wp_query object) $query
 * 
 * @return Void
 */
function woocommerce_filter_posts_filter( $query ){
    global $pagenow;
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if ( 'product' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['Featured']) && $_GET['Featured'] != '') {
        $query->query_vars['meta_key'] = '_featured';
        $query->query_vars['meta_value'] = $_GET['Featured'];
    }
}
