/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {
  useBlockProps,
  InspectorControls,
} from "@wordpress/block-editor";

import { 
  Panel,
  PanelBody,
  PanelRow,
  ToggleControl,
  SelectControl,
  RangeControl,
  RadioControl,
  FormTokenField,
  __experimentalNumberControl as NumberControl,
  Placeholder,
  Spinner,
} from '@wordpress/components';

import { useEffect, useState } from '@wordpress/element';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ 
  attributes, 
  setAttributes, 
  relatedPosts, 
  currentPostId, 
  currentPostCategories, 
  allCategories, 
  postType 
}) {
  const { 
    postsToShow, 
    showFeaturedImage, 
    showExcerpt, 
    showDate, 
    showCategories, 
    showAuthor,
    excludeCurrentPost,
    orderBy,
    order,
    layout,
    columns,
    enablePagination,
    categoryFiltering,
    specificCategories,
    imageSize,
    excerptLength
  } = attributes;

  const [selectedCategories, setSelectedCategories] = useState([]);

  useEffect(() => {
    if (allCategories && specificCategories.length > 0) {
      const categoryNames = specificCategories.map(catId => {
        const category = allCategories.find(cat => cat.id === catId);
        return category ? category.name : '';
      }).filter(Boolean);
      
      setSelectedCategories(categoryNames);
    }
  }, [allCategories, specificCategories]);

  const onCategoryChange = (tokens) => {
    if (!allCategories) return;
    
    const availableCategories = allCategories.map(cat => cat.name);
    const validTokens = tokens.filter(token => availableCategories.includes(token));
    
    setSelectedCategories(validTokens);
    
    const newCategoryIds = validTokens.map(token => {
      const category = allCategories.find(cat => cat.name === token);
      return category ? category.id : null;
    }).filter(Boolean);
    
    setAttributes({ specificCategories: newCategoryIds });
  };

  const blockProps = useBlockProps({
    className: `related-posts-layout-${layout} columns-${columns}`
  });

  const layoutOptions = [
    { label: __('List', 'my-theme'), value: 'list' },
    { label: __('Grid', 'my-theme'), value: 'grid' },
    { label: __('Card', 'my-theme'), value: 'card' },
  ];

  const orderByOptions = [
    { label: __('Date', 'my-theme'), value: 'date' },
    { label: __('Title', 'my-theme'), value: 'title' },
    { label: __('Random', 'my-theme'), value: 'rand' },
    { label: __('Comment Count', 'my-theme'), value: 'comment_count' },
  ];

  const orderOptions = [
    { label: __('Descending', 'my-theme'), value: 'desc' },
    { label: __('Ascending', 'my-theme'), value: 'asc' },
  ];

  const imageSizeOptions = [
    { label: __('Thumbnail', 'my-theme'), value: 'thumbnail' },
    { label: __('Medium', 'my-theme'), value: 'medium' },
    { label: __('Large', 'my-theme'), value: 'large' },
    { label: __('Full', 'my-theme'), value: 'full' },
  ];

  const isLoading = relatedPosts === null;
  const hasNoPosts = Array.isArray(relatedPosts) && relatedPosts.length === 0;

  return (
    <>
      <InspectorControls>
        <Panel>
          <PanelBody title={__("Content Settings", "my-theme")} initialOpen={true}>
            <PanelRow>
              <NumberControl
                label={__("Number of posts to show", "my-theme")}
                labelPosition="top"
                value={postsToShow}
                onChange={(value) => setAttributes({ postsToShow: parseInt(value) })}
                min={1}
                max={20}
              />
            </PanelRow>
            
            <PanelRow>
              <RadioControl
                label={__("Category Filtering", "my-theme")}
                selected={categoryFiltering}
                options={[
                  { label: __("Use current post's categories", "my-theme"), value: "current" },
                  { label: __("Select specific categories", "my-theme"), value: "specific" },
                  { label: __("Show all categories", "my-theme"), value: "all" },
                ]}
                onChange={(value) => setAttributes({ categoryFiltering: value })}
              />
            </PanelRow>
            
            {categoryFiltering === 'specific' && allCategories && (
              <PanelRow>
                <FormTokenField
                  label={__("Select Categories", "my-theme")}
                  value={selectedCategories}
                  suggestions={allCategories ? allCategories.map(cat => cat.name) : []}
                  onChange={onCategoryChange}
                  maxSuggestions={20}
                />
              </PanelRow>
            )}
            
            <PanelRow>
              <SelectControl
                label={__("Order By", "my-theme")}
                value={orderBy}
                options={orderByOptions}
                onChange={(value) => setAttributes({ orderBy: value })}
              />
            </PanelRow>
            
            <PanelRow>
              <SelectControl
                label={__("Order", "my-theme")}
                value={order}
                options={orderOptions}
                onChange={(value) => setAttributes({ order: value })}
              />
            </PanelRow>
            
            <PanelRow>
              <ToggleControl
                label={__("Exclude Current Post", "my-theme")}
                checked={excludeCurrentPost}
                onChange={() => setAttributes({ excludeCurrentPost: !excludeCurrentPost })}
              />
            </PanelRow>
            
            <PanelRow>
              <ToggleControl
                label={__("Enable Pagination", "my-theme")}
                checked={enablePagination}
                onChange={() => setAttributes({ enablePagination: !enablePagination })}
              />
            </PanelRow>
          </PanelBody>
          
          <PanelBody title={__("Layout Settings", "my-theme")} initialOpen={false}>
            <PanelRow>
              <RadioControl
                label={__("Layout Style", "my-theme")}
                selected={layout}
                options={layoutOptions}
                onChange={(value) => setAttributes({ layout: value })}
              />
            </PanelRow>
            
            {layout !== 'list' && (
              <PanelRow>
                <RangeControl
                  label={__("Columns", "my-theme")}
                  value={columns}
                  onChange={(value) => setAttributes({ columns: value })}
                  min={1}
                  max={4}
                />
              </PanelRow>
            )}
            
            <PanelRow>
              <SelectControl
                label={__("Image Size", "my-theme")}
                value={imageSize}
                options={imageSizeOptions}
                onChange={(value) => setAttributes({ imageSize: value })}
              />
            </PanelRow>
            
            <PanelRow>
              <NumberControl
                label={__("Excerpt Length (words)", "my-theme")}
                labelPosition="top"
                value={excerptLength}
                onChange={(value) => setAttributes({ excerptLength: parseInt(value) })}
                min={10}
                max={100}
              />
            </PanelRow>
          </PanelBody>
          
          <PanelBody title={__("Display Settings", "my-theme")} initialOpen={false}>
            <PanelRow>
              <ToggleControl
                label={__("Show Featured Image", "my-theme")}
                checked={showFeaturedImage}
                onChange={() => setAttributes({ showFeaturedImage: !showFeaturedImage })}
              />
            </PanelRow>
            
            <PanelRow>
              <ToggleControl
                label={__("Show Excerpt", "my-theme")}
                checked={showExcerpt}
                onChange={() => setAttributes({ showExcerpt: !showExcerpt })}
              />
            </PanelRow>
            
            <PanelRow>
              <ToggleControl
                label={__("Show Date", "my-theme")}
                checked={showDate}
                onChange={() => setAttributes({ showDate: !showDate })}
              />
            </PanelRow>
            
            <PanelRow>
              <ToggleControl
                label={__("Show Categories", "my-theme")}
                checked={showCategories}
                onChange={() => setAttributes({ showCategories: !showCategories })}
              />
            </PanelRow>
            
            <PanelRow>
              <ToggleControl
                label={__("Show Author", "my-theme")}
                checked={showAuthor}
                onChange={() => setAttributes({ showAuthor: !showAuthor })}
              />
            </PanelRow>
          </PanelBody>
        </Panel>
      </InspectorControls>
      
      <div {...blockProps}>
        {isLoading ? (
          <Placeholder icon="update" label={__("Loading related posts...", "my-theme")}>
            <Spinner />
          </Placeholder>
        ) : hasNoPosts ? (
          <Placeholder icon="warning" label={__("No related posts found", "my-theme")}>
            <p>
              {categoryFiltering === 'current' ? 
                __("Try changing the category filtering options or increasing the number of posts to show.", "my-theme") : 
                __("Try changing the filtering options or increasing the number of posts to show.", "my-theme")
              }
            </p>
          </Placeholder>
        ) : (
          <div className={`related-posts-container ${layout}-layout`}>
            <div className={`related-posts-grid columns-${columns}`}>
              {relatedPosts.map((post) => {
                const featuredImage = post._embedded && 
                  post._embedded['wp:featuredmedia'] && 
                  post._embedded['wp:featuredmedia'][0];
                
                const postCategories = post._embedded && 
                  post._embedded['wp:term'] && 
                  post._embedded['wp:term'][0];
                
                const author = post._embedded && 
                  post._embedded['author'] && 
                  post._embedded['author'][0];
                
                return (
                  <article key={post.id} className="related-post">
                    {showFeaturedImage && featuredImage && featuredImage.source_url && (
                      <div className="related-post-image">
                        <img 
                          src={featuredImage.media_details.sizes[imageSize]?.source_url || featuredImage.source_url} 
                          alt={featuredImage.alt_text || post.title.rendered} 
                        />
                      </div>
                    )}
                    
                    <div className="related-post-content">
                      <h3 className="related-post-title">
                        <a href="#" onClick={(e) => e.preventDefault()}>
                          {post.title.rendered ? (
                            <span dangerouslySetInnerHTML={{ __html: post.title.rendered }} />
                          ) : (
                            __("(No title)", "my-theme")
                          )}
                        </a>
                      </h3>
                      
                      {showCategories && postCategories && postCategories.length > 0 && (
                        <div className="related-post-categories">
                          {postCategories.map((category, index) => (
                            <span key={category.id} className="related-post-category">
                              {category.name}
                              {index < postCategories.length - 1 ? ', ' : ''}
                            </span>
                          ))}
                        </div>
                      )}
                      
                      {showDate && (
                        <div className="related-post-date">
                          {new Date(post.date).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                          })}
                        </div>
                      )}
                      
                      {showAuthor && author && (
                        <div className="related-post-author">
                          {__("By", "my-theme")} {author.name}
                        </div>
                      )}
                      
                      {showExcerpt && post.excerpt && (
                        <div className="related-post-excerpt">
                          <div dangerouslySetInnerHTML={{ 
                            __html: post.excerpt.rendered.split(' ').slice(0, excerptLength).join(' ') + '...' 
                          }} />
                        </div>
                      )}
                    </div>
                  </article>
                );
              })}
            </div>
            
            {enablePagination && (
              <div className="related-posts-pagination">
                <span className="pagination-text">
                  {__("Pagination will be enabled on the frontend", "my-theme")}
                </span>
              </div>
            )}
          </div>
        )}
      </div>
    </>
  );
}