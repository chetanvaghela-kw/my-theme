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


 import { Panel,  PanelRow, PanelBody, ToggleControl,  __experimentalNumberControl as NumberControl } from '@wordpress/components';

import { useEffect } from 'react';
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
export default function Edit({ postslist, attributes, setAttributes, clientId }) {

    const { showFeaturedImage, showDescription, showDate } = attributes;

	return (
        <>
          <InspectorControls>
                <Panel header={__("Setting", "my-theme")}>
                <React.Fragment key=".0">
                    <PanelBody
                    className="my-theme-head"
                    title={__("Genaral", "my-theme")}
                    >
                        <PanelRow>
								<div style={{width:'100%'}}>
									<NumberControl	
										label="Number of posts listing"
										labelPosition="top"
										max={20}
										min={1}
										onChange={( Maximum ) => setAttributes( { Maximum } )}
										value={attributes.Maximum}
									/>
								</div>
							</PanelRow>			

                        <PanelRow>
                            <ToggleControl
                                checked={ showFeaturedImage }
                                label={ __(
                                    'Show Featured Image',
                                    'my-theme'
                                ) }
                                onChange={ () =>
                                    setAttributes( {
                                        showFeaturedImage: ! showFeaturedImage,
                                    } )
                                }
                            />
                        </PanelRow>
                        <PanelRow>
                            <ToggleControl
                                checked={ showDescription }
                                label={ __(
                                    'Show Description',
                                    'my-theme'
                                ) }
                                onChange={ () =>
                                    setAttributes( {
                                        showDescription: ! showDescription,
                                    } )
                                }
                            />
                        </PanelRow>
                        <PanelRow>
                            <ToggleControl
                                checked={ showDate }
                                label={ __(
                                    'Show Description',
                                    'my-theme'
                                ) }
                                onChange={ () =>
                                    setAttributes( {
                                        showDate: ! showDate,
                                    } )
                                }
                            />
                        </PanelRow>
                                            
                    </PanelBody>
                </React.Fragment>
                </Panel>
            </InspectorControls>
            <div { ...useBlockProps() }>
                {
                    postslist && (
                        <div>
                        {
                            postslist.map((data)=>(
                                data?.featured_src && data?.title?.rendered ? (
                                    <div>
                                        <div>                                                              
                                            <div>
                                                <a href="#" >{data?.title?.rendered}</a>
                                            </div>   
                                            { showDate && (                                                 
                                                <div>  
                                                    {new Date(data?.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}
                                                </div>   
                                                )
                                            }
                                            { showDescription && (  
                                                <div dangerouslySetInnerHTML={{ __html: data?.excerpt?.rendered }} />
                                             )
                                            }   
                                            { showFeaturedImage && (                                         
                                                <div>
                                                    <img src={data?.featured_src} width="451" height="260" alt=""/>
                                                </div>
                                                )
                                            } 
                                        </div>												
                                    </div>
                                ) : ('')
                            ))
                        }
                        </div>
                    )
                }
            </div>
        </>
    );
}
