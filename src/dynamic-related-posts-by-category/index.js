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

const relatedPostsIcon = (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    aria-hidden="true"
    focusable="false"
  >
    <path d="M16 19H3v-2h13v2zm5-10H3v2h18V9zM3 5v2h11V5H3zm14 0v2h4V5h-4zm-6 8v2h10v-2H11zm-8 0v2h5v-2H3z"></path>
  </svg>
);

registerBlockType(metadata.name, {
  icon: relatedPostsIcon,
  
  /**
   * @see ./edit.js
   */
  edit: withSelect((select, ownProps) => {
    const { attributes } = ownProps;
    const { getEntityRecords, getCurrentPostId, getPostType } = select('core');
    const { getCurrentPostType } = select('core/editor');

    // Get current post info
    const currentPostId = getCurrentPostId?.();
    const currentPostType = getCurrentPostType?.();

    // Get categories for the current post
    let currentPostCategories = [];
    let allCategories = [];

    if (currentPostType === 'post') {
      const post = select('core').getEditedEntityRecord('postType', 'post', currentPostId);
      if (post && post.categories) {
        currentPostCategories = post.categories;
      }

      // Get all categories for the select control
      allCategories = getEntityRecords('taxonomy', 'category', { per_page: -1 }) || [];
    }

    // Build query args
    const queryArgs = {
      per_page: attributes.postsToShow,
      _embed: true,
      order: attributes.order,
      orderby: attributes.orderBy,
      exclude: attributes.excludeCurrentPost ? [currentPostId] : [],
    };

    // Add category filtering
    if (attributes.categoryFiltering === 'current' && currentPostCategories.length > 0) {
      queryArgs.categories = currentPostCategories;
    } else if (attributes.categoryFiltering === 'specific' && attributes.specificCategories.length > 0) {
      queryArgs.categories = attributes.specificCategories;
    }

    // Get posts
    const relatedPosts = getEntityRecords('postType', 'post', queryArgs) || [];

    return {
      relatedPosts,
      currentPostId,
      currentPostCategories,
      allCategories,
      postType: getPostType?.(currentPostType),
    };
  })(Edit),
});