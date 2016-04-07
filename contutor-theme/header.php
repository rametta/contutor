<?php
/**
 * The template for displaying the header
 *
 *
 * @package WordPress
 * @subpackage Contutor
 * @since Contutor 1.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <!-- Created with love by the Contutor development team -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Contutor.ca is the leading platform for Concordia students to connect to reputable peer tutors in the Montreal area.">
    <meta name="author" content="Jason Rametta, Akshar Patel, Oscar Bobadilla, Alex Patenaude">
    <meta name="location" content="Montreal, Quebec, Canada">
    <meta name="keywords" content="tutor, turtoring, montreal, concordia, jmsb, john molson school of business, crash course, affordable, cheap">
    <meta name="subtitle" content="Concordia University Tutoring">
    <meta name="url" content="http://contutor.ca">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:700,400&amp;subset=cyrillic,latin,greek,vietnamese">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/contutor.css" type="text/css" />
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/socicon.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/fonts/socicon.svg" type="text/css" />
    
    <title><?php echo get_bloginfo( 'name', 'display' ) . ' - ' . get_the_title(); ?></title>
    
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
<script>
    /* Contutor Analytics Start */
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-57040803-2', 'auto');
    ga('send', 'pageview');
    /* Contutor Analytics End */
</script>
    
<?php
    $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
    $feat_title = get_the_title();
    $feat_caption = get_post_meta($post->ID, 'page-caption', true);

    $exceptions = array("CONCORDIA UNIVERSITY TUTORING", "COURSES", "TUTORS", "PROGRAMS");
    $show_banner = in_array($feat_title, $exceptions);
?>
    
<!-- Navigation -->
<section class="con-navbar con-navbar--freeze con-navbar--absolute con-navbar--sticky con-navbar--auto-collapse <?php if($show_banner){echo 'con-navbar--transparent';} ?>" id="menu-93">
    <div class="con-navbar__section con-section">
        <div class="con-section__container container">
            <div class="con-navbar__container">
                <div class="con-navbar__column con-navbar__column--s con-navbar__brand">
                    <span class="con-navbar__brand-link con-brand con-brand--inline">
                        <span class="con-brand__logo"><a href="http://contutor.ca/"><img class="con-navbar__brand-img con-brand__img" src="<?php echo get_site_icon_url(); ?>" alt="Contutor"></a></span>
                        <span class="con-brand__name"><a class="con-brand__name text-white" href="http://contutor.ca/">CONTUTOR</a></span>
                    </span>
                </div>
                <div class="con-navbar__hamburger con-hamburger text-white"><span class="con-hamburger__line"></span></div>
                <div class="con-navbar__column con-navbar__menu">
                    <nav class="con-navbar__menu-box con-navbar__menu-box--inline-right">
                        <div class="con-navbar__column">
                            <ul class="con-navbar__items con-navbar__items--right con-buttons con-buttons--freeze con-buttons--right btn-decorator con-buttons--active">
                                <?php
                                /* loop for menu*/
                                $menu_name = 'contutor-menu';

                                if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
                                $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
                                $menu_items = wp_get_nav_menu_items($menu->term_id);

                                foreach ( (array) $menu_items as $key => $menu_item ) {
                                    $title = $menu_item->title;
                                    $url = $menu_item->url;
                                    $menu_list .= '<li class="con-navbar__item"><a class="con-buttons__link btn text-white" href="' . $url . '">' . $title . '</a></li>';
                                }
                                } else {
                                $menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
                                }

                                echo $menu_list;
				
                                if(!is_user_logged_in()){
                                    echo '<li class="con-navbar__item"><a class="con-buttons__link btn text-white" href="http://contutor.ca/wp-login.php">LOGIN</a></li>';
                                }
                                ?>
                                
                                <!-- Search Bar -->
                                <li class="con-navbar__item">
                                    <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
                                        <input type="text" value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" class="search-bar con" style="width:100px;" placeholder="SEARCH">
                                    </form>
                                </li>
                                
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
    
<!-- Banner -->
<?php 
    if ($show_banner):
?>
    
<section class="content-2 simple col-1 col-undefined con-parallax-background con-after-navbar" id="content5-92" style="background-image: url(<?php echo $feat_image;?>);">
    <div class="con-overlay" style="opacity: 0.6; background-color: rgb(0, 0, 0);"></div>
    <div class="container">
        <div class="row">
            <div>
                <div class="thumbnail">
                    <div class="caption">
                        <h3><?php echo $feat_title; ?></h3>
                        <div><p><?php echo $feat_caption; ?><br></p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    
<?php
    endif;
?>