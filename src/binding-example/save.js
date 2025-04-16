import { useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps} style={{ backgroundColor: attributes.backgroundColor, padding: '20px', color: 'white', borderRadius: '8px' }}>
            <p>This block uses the Binding API.</p>
        </div>
    );
}
