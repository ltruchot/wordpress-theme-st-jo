<?php
/**
 * The header for our theme
 *
 * This is the template that displays the `head` element and everything up
 * until the `#content` element.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package st-jo
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page">
	<?php
	/*
	 * The skip link only earns its place if it becomes visible when focused:
	 * `sr-only` alone hides it from the keyboard user it exists for.
	 */
	?>
	<a
		href="#content"
		class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded focus:bg-background focus:px-4 focus:py-2 focus:text-primary focus:shadow-lg focus:outline focus:outline-2 focus:outline-primary"
	><?php esc_html_e( 'Aller au contenu', 'st-jo' ); ?></a>

	<?php get_template_part( 'template-parts/layout/header', 'content' ); ?>

	<div id="content">
