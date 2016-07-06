<?php
/**
 * [property_terms] template
 *
 * To modify it, copy it to your theme's root.
 */

$list = get_the_term_list( $property_id, $taxonomy, '<li>', '</li><li>', '</li>' );
if(trim($list) != ""):
if($title != ''){
	echo '<h2 class="widget-title">'. $title .'</h2>';
}
echo '<ul class="' . $taxonomy .'_list">';
	echo $list;
echo '</ul>';
endif;