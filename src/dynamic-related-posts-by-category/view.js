/**
 * Use this file for JavaScript code that you want to run in the front-end 
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize elements
    const relatedPostsContainers = document.querySelectorAll('.wp-block-my-theme-related-posts-by-category');
    
    if (!relatedPostsContainers.length) return;
    
    relatedPostsContainers.forEach(container => {
      // Add hover effects for cards
      if (container.classList.contains('related-posts-layout-card')) {
        const cards = container.querySelectorAll('.related-post');
        
        cards.forEach(card => {
          card.addEventListener('mouseenter', function() {
            this.classList.add('hover');
          });
          
          card.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
          });
        });
      }
      
      // Handle image loading
      const images = container.querySelectorAll('.related-post-image img');
      
      images.forEach(img => {
        img.addEventListener('load', function() {
          this.classList.add('loaded');
        });
        
        // If image is already loaded
        if (img.complete) {
          img.classList.add('loaded');
        }
      });
    });
  });