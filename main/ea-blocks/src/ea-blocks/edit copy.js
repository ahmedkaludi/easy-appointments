import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl, ToggleControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

export default function Edit({ attributes, setAttributes }) {
    const { width, scrollOff, layoutCols, location, service, worker, defaultDate } = attributes;
    const blockProps = useBlockProps(); // ✅ Correct placement inside the function

    const [locations, setLocations] = useState([]);
    const [services, setServices] = useState([]);
    const [workers, setWorkers] = useState([]);

    useEffect(() => {
        fetchOptions('location', setLocations);
    }, []);

    useEffect(() => {
        if (location) fetchOptions('service', setServices, { location_id: location });
    }, [location]);

    useEffect(() => {
        if (service) fetchOptions('worker', setWorkers, { service_id: service });
    }, [service]);

    const fetchOptions = async (type, setState, params = {}) => {
        const queryString = new URLSearchParams(params).toString();
        try {
            const response = await apiFetch({ path: `/wp/v2/eablocks/get_ea_options?type=${type}&${queryString}` });
            setState(response);
        } catch (error) {
            console.error(`Error fetching ${type} options:`, error);
        }
    };

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('Block Settings', 'ea-blocks')}>
                    <TextControl
                        label={__('Width', 'ea-blocks')}
                        value={width}
                        onChange={(value) => setAttributes({ width: value })}
                        placeholder="400px"
                    />
                    <ToggleControl
                        label={__('Disable Scroll?', 'ea-blocks')}
                        checked={scrollOff === 'true'}
                        onChange={(value) => setAttributes({ scrollOff: value ? 'true' : 'false' })}
                    />
                    <SelectControl
                        label={__('Layout Columns', 'ea-blocks')}
                        value={layoutCols}
                        options={[
                            { label: '1 Column', value: '1' },
                            { label: '2 Columns', value: '2' }
                        ]}
                        onChange={(value) => setAttributes({ layoutCols: value })}
                    />
                    <SelectControl
                        label={__('Location', 'ea-blocks')}
                        value={location}
                        options={[{ label: 'Select Location', value: '' }, ...locations]}
                        onChange={(value) => {
                            setAttributes({ location: value, service: '', worker: '' });
                            setServices([]);
                            setWorkers([]);
                        }}
                    />
                    <SelectControl
                        label={__('Service', 'ea-blocks')}
                        value={service}
                        options={[{ label: 'Select Service', value: '' }, ...services]}
                        onChange={(value) => {
                            setAttributes({ service: value, worker: '' });
                            setWorkers([]);
                        }}
                        disabled={!location}
                    />
                    <SelectControl
                        label={__('Worker', 'ea-blocks')}
                        value={worker}
                        options={[{ label: 'Select Worker', value: '' }, ...workers]}
                        onChange={(value) => setAttributes({ worker: value })}
                        disabled={!service}
                    />
                    <TextControl
                        label={__('Default Date', 'ea-blocks')}
                        value={defaultDate}
                        onChange={(value) => setAttributes({ defaultDate: value })}
                        placeholder="YYYY-MM-DD or +5d"
                    />
                </PanelBody>
            </InspectorControls>

            <p>
                <strong>{__('Ea Blocks – Vikas!', 'ea-blocks')}</strong>
            </p>
            <p>
                <code>
                    [ea_bootstrap
                    {width ? ` width="${width}"` : ''}
                    {scrollOff ? ` scroll_off="true"` : ''}
                    {layoutCols ? ` layout_cols="${layoutCols}"` : ''}
                    {location ? ` location="${location}"` : ''}
                    {service ? ` service="${service}"` : ''}
                    {worker ? ` worker="${worker}"` : ''}
                    {defaultDate ? ` default_date="${defaultDate}"` : ''}
                    ]
                </code>
            </p>
        </div>
    );
}
