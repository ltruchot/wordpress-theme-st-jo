/**
 * Block editor modifications
 *
 * This file is loaded only by the block editor. Use it to modify the block
 * editor via its APIs.
 *
 * The JavaScript code you place here will be processed by esbuild, and the
 * output file will be created at `../theme/js/block-editor.min.js` and
 * enqueued in `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

/**
 * This import adds your front-end post title and Tailwind Typography classes
 * to the block editor. It also adds some helper classes so you can access the
 * post type when modifying the block editor’s appearance.
 */
import '@_tw/typography/block-editor-classes';

wp.domReady(() => {
	/**
	 * Add support for Tailwind Typography’s `lead` class via a block style.
	 */
	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'lead',
		label: 'Lead',
	});

	/**
	 * Add container variation for Group block with beige background and bottom wave border
	 */
	wp.blocks.registerBlockStyle('core/group', {
		name: 'container-beige-wave',
		label: 'Beige et vagues',
		className: 'is-style-container-beige-wave',
	});

	/**
	 * Add MEA Rouge variation for Group block
	 */
	wp.blocks.registerBlockStyle('core/group', {
		name: 'mea-rouge',
		label: 'MEA Rouge',
		className: 'is-style-mea-rouge',
	});

	/**
	 * Add Icon Title variation for Group block
	 */
	wp.blocks.registerBlockStyle('core/group', {
		name: 'icon-title',
		label: 'Icône et titre',
		className: 'is-style-icon-title',
	});

	/**
	 * Add button variations with chevron
	 */
	wp.blocks.registerBlockStyle('core/button', {
		name: 'chevron-right',
		label: 'Chevron droit',
	});

	wp.blocks.registerBlockStyle('core/button', {
		name: 'outline-chevron-right',
		label: 'Contour chevron droit',
	});

	/**
	 * Add Round Image Link variations for Group block
	 */
	wp.blocks.registerBlockStyle('core/group', {
		name: 'round-image-link',
		label: 'Lien image ronde',
		className: 'is-style-round-image-link',
	});

	wp.blocks.registerBlockStyle('core/group', {
		name: 'round-image-links-container',
		label: 'Conteneur liens images rondes',
		className: 'is-style-round-image-links-container',
	});
});
