import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save() {
    return (
        <div {...useBlockProps.save()}>
            <RichText.Content tagName="h2" />
            <RichText.Content tagName="p" />
            <img src="" alt="Custom Image" />
        </div>
    );
}
