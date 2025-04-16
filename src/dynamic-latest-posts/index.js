/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';
import { withSelect } from '@wordpress/data';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */

const calendarIcon = (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		aria-hidden="true"
		focusable="false"
	>
		<path d="M16 19H3v-2h13v2zm5-10H3v2h18V9zM3 5v2h11V5H3zm14 0v2h4V5h-4zm-6 8v2h10v-2H11zm-8 0v2h5v-2H3z"></path>
	</svg>
);
registerBlockType( metadata.name, {

	icon: calendarIcon,
	/**
	 * @see ./edit.js
	 */
	edit:withSelect((select, { attributes, setAttributes } ) => {
		const { getEntityRecords } = select('core');
		const perpage =  attributes.Maximum ? attributes.Maximum : 1;
		const posttype =  'post';		
		const queryArgs = {
			per_page: perpage,
			_fields: ['id', 'title', 'featured_media', 'featured_src', 'excerpt','date'],
		};
	
		const postslist = getEntityRecords('postType', posttype, queryArgs);
     
		return {
			postslist,
		}	
    })(Edit),
} );
