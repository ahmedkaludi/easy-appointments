import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl, ToggleControl, DatePicker } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { format } from 'date-fns';

export default function Edit({ attributes, setAttributes }) {
    const { width, scrollOff, layoutCols, location, service, worker, defaultDate } = attributes;
    const blockProps = useBlockProps();

    const [locations, setLocations] = useState([]);
    const [services, setServices] = useState([]);
    const [workers, setWorkers] = useState([]);
    const [selectedDate, setSelectedDate] = useState(defaultDate);
    const [slots, setSlots] = useState([]);

    useEffect(() => {
        fetchOptions('location', setLocations);
    }, []);

    useEffect(() => {
        if (location) fetchOptions('service', setServices, { location_id: location });
    }, [location]);

    useEffect(() => {
        if (service) fetchOptions('worker', setWorkers, { service_id: service });
    }, [service]);

    useEffect(() => {
        setSelectedDate(defaultDate);
        if (defaultDate) fetchTimeSlots(defaultDate);
    }, [defaultDate]);

    const fetchOptions = async (type, setState, params = {}) => {
        const queryString = new URLSearchParams(params).toString();
        try {
            const response = await apiFetch({ path: `/wp/v2/eablocks/get_ea_options?type=${type}&${queryString}` });
            setState(response);
        } catch (error) {
            console.error(`Error fetching ${type} options:`, error);
        }
    };

    const fetchTimeSlots = async (date) => {
        try {
            const sampleSlots = [
                "10:00 AM", "10:30 AM", "11:00 AM", "11:30 AM",
                "12:00 PM", "12:30 PM", "1:00 PM", "1:30 PM",
                "2:00 PM", "2:30 PM", "3:00 PM", "3:30 PM",
                "4:00 PM", "4:30 PM", "5:00 PM", "5:30 PM",
                "6:00 PM", "6:30 PM", "7:00 PM", "7:30 PM"
            ];
            setSlots(sampleSlots);
        } catch (error) {
            console.error("Error fetching slots:", error);
        }
    };

    const handleDateChange = (date) => {
        const formattedDate = format(new Date(date), 'yyyy-MM-dd');
        setSelectedDate(formattedDate);
        setAttributes({ defaultDate: formattedDate });
        fetchTimeSlots(formattedDate);
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

                    
                    <label>{__('Select Default Date', 'ea-blocks')}</label>
                    <DatePicker currentDate={defaultDate} onChange={handleDateChange} />
                </PanelBody>
            </InspectorControls>

            
            <div className="ea_preview">
                <label>{__('Location', 'ea-blocks')}</label>
                <select 
                    value={location} 
                    onChange={(e) => setAttributes({ location: e.target.value, service: '', worker: '' })}
                >
                    <option value="">{__('Select Location', 'ea-blocks')}</option>
                    {locations.map((loc) => (
                        <option key={loc.value} value={loc.value}>
                            {loc.label}
                        </option>
                    ))}
                </select>

                <label>{__('Service', 'ea-blocks')}</label>
                <select 
                    value={service} 
                    onChange={(e) => setAttributes({ service: e.target.value, worker: '' })}
                    disabled={!location}
                >
                    <option value="">{__('Select Service', 'ea-blocks')}</option>
                    {services.map((srv) => (
                        <option key={srv.value} value={srv.value}>
                            {srv.label}
                        </option>
                    ))}
                </select>

                <label>{__('Worker', 'ea-blocks')}</label>
                <select 
                    value={worker} 
                    onChange={(e) => setAttributes({ worker: e.target.value })}
                    disabled={!service}
                >
                    <option value="">{__('Select Worker', 'ea-blocks')}</option>
                    {workers.map((wrk) => (
                        <option key={wrk.value} value={wrk.value}>
                            {wrk.label}
                        </option>
                    ))}
                </select>

                
                <div className="ea_calendar">
                    <label>{__('Selected Date', 'ea-blocks')}</label>
                    <DatePicker currentDate={selectedDate} onChange={handleDateChange} />
                </div>

                
                <div className="ea_slots">
                    <h4>{__('Available Time Slots', 'ea-blocks')}</h4>
                    {slots.length > 0 ? (
                        <div className="ea_slot_grid">
                            {slots.map((slot, index) => (
                                <div key={index} className="ea_slot">
                                    {slot}
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p>{__('No available slots', 'ea-blocks')}</p>
                    )}
                </div>
            </div>

            
            <style>{`
                .ea_preview {
                    max-width: 400px;
                    background: #fff;
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .ea_preview label {
                    display: block;
                    margin-top: 10px;
                    font-weight: bold;
                }
                .ea_preview select {
                    width: 100%;
                    padding: 5px;
                    margin-top: 5px;
                }
                .ea_calendar {
                    margin-top: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    padding: 10px;
                    text-align: center;
                    font-size: 16px;
                    background: #f9f9f9;
                }
                .ea_slots {
                    margin-top: 10px;
                    text-align: center;
                }
                .ea_slot_grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 5px;
                    margin-top: 10px;
                }
                .ea_slot {
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 3px;
                    background: #f1f1f1;
                    cursor: pointer;
                    color: #21759b;
                }
                .ea_slot:hover {
                    background: #ddd;
                }
            `}</style>
        </div>
    );
}
