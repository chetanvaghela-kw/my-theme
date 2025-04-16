import { useBlockProps, RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

export default function Edit() {
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <RichText tagName="h2" placeholder="Enter title..." />

            <MediaUploadCheck>
                <MediaUpload
                    allowedTypes={['image']}
                    render={({ open }) => (
                        <Button onClick={open} className="button">
                            Upload Image
                        </Button>
                    )}
                />
            </MediaUploadCheck>
        </div>
    );
}
