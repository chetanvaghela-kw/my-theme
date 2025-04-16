import { useBlockProps, useBlockBindings } from '@wordpress/block-editor';
import { PanelBody, ColorPalette } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';

export default function Edit({ attributes, setAttributes }) {
    const blockProps = useBlockProps();

    // Check if the Binding API is available
    const hasBindingAPI = typeof useBlockBindings === 'function';

    // Initialize Binding API (fallback if not available)
    const bindingAPI = hasBindingAPI ? useBlockBindings({ name: `my-theme-binding-example` }) : { bind: () => {}, unbind: () => {} };

    // Handle Color Change
    const onChangeBackgroundColor = (newColor) => {
        setAttributes({ backgroundColor: newColor });
    };

    // Define styles
    const dynamicStyles = {
        backgroundColor: attributes.backgroundColor,
        padding: '20px',
        color: 'white',
        borderRadius: '8px',
    };

    // Apply Binding API when attributes change
    useEffect(() => {
        if (hasBindingAPI) {
            bindingAPI.bind('style', dynamicStyles);

            // Cleanup function when component unmounts
            return () => {
                bindingAPI.unbind('style');
            };
        }
    }, [attributes.backgroundColor]);

    return (
        <>
            <InspectorControls>
                <PanelBody title="Background Color">
                    <ColorPalette value={attributes.backgroundColor} onChange={onChangeBackgroundColor} />
                </PanelBody>
            </InspectorControls>
            <div {...blockProps} style={dynamicStyles}>
                <p>This block uses the Binding API.</p>
            </div>
        </>
    );
}
