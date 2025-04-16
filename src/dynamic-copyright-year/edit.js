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


 import { Panel, PanelRow, PanelBody, TextControl, ToggleControl } from '@wordpress/components';

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
export default function Edit({ attributes, setAttributes, clientId }) {

    const { fallbackCurrentYear, showStartingYear, startingYear } = attributes;

	// Get the current year and make sure it's a string.
	const currentYear = new Date().getFullYear().toString();

	// When the block loads, set the fallbackCurrentYear attribute to the
	// current year if it's not already set.
	useEffect( () => {
		if ( currentYear !== fallbackCurrentYear ) {
			setAttributes( { fallbackCurrentYear: currentYear } );
		}
	}, [ currentYear, fallbackCurrentYear, setAttributes ] );

	let displayDate;

	// Display the starting year as well if supplied by the user.
	if ( showStartingYear && startingYear ) {
		displayDate = startingYear + '–' + currentYear;
	} else {
		displayDate = currentYear;
	}


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
                        <ToggleControl
                            checked={ showStartingYear }
                            label={ __(
                                'Show starting year',
                                'my-theme'
                            ) }
                            onChange={ () =>
                                setAttributes( {
                                    showStartingYear: ! showStartingYear,
                                } )
                            }
                        />
                         </PanelRow>
                        { showStartingYear && (
                             <PanelRow>
                            <TextControl
                                label={ __(
                                    'Starting year',
                                    'my-theme'
                                ) }
                                value={ startingYear }
                                onChange={ ( value ) =>
                                    setAttributes( { startingYear: value } )
                                }
                                />
                                </PanelRow>                            
                        ) }                        
                    </PanelBody>
                </React.Fragment>
                </Panel>
            </InspectorControls>
            <p { ...useBlockProps() }>© { displayDate }</p>
        </>
    );
}
