import { useBlockProps, useBlockBindings } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';

export default function Edit() {
    const blockProps = useBlockProps();
    const [apiData, setApiData] = useState(null);

    // Initialize Binding API
    const hasBindingAPI = typeof useBlockBindings === 'function';
    const bindingAPI = hasBindingAPI ? useBlockBindings({ name: `my-theme/api-integration` }) : { bind: () => {}, unbind: () => {} };

    // Fetch data from API
    useEffect(() => {
        fetch('https://jsonplaceholder.typicode.com/posts/1')
            .then(response => response.json())
            .then(data => {
                const jsonData = JSON.stringify(data, null, 2)
                setApiData(jsonData);
                if (hasBindingAPI) {
                    bindingAPI.bind('apiData', jsonData);
                }
            });

        return () => {
            if (hasBindingAPI) bindingAPI.unbind('apiData');
        };
    }, []);

    return (
        <div {...blockProps}>
            <h3>API Data:</h3>
                { apiData ? (
                    <div className="my-theme-api-block">
                        <h3>{JSON.parse(apiData).title}</h3>
                        <p>{JSON.parse(apiData).body}</p>
                    </div>
                ) : "Loading..." }
        </div>
    );
}
