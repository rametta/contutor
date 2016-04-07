<?php
/**
 * Archive results
 *
 * @package WordPress
 * @subpackage Contutor
 * @since Contutor 1.0
 */

/* Archive */

get_header();

echo "archive";

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
	   echo the_content();
	endwhile;
endif;

get_footer();
?>