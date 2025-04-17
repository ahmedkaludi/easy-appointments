import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, SelectControl } from "@wordpress/components";
import { useState, useEffect, useRef } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import "@fullcalendar/common/main.css";
import "@fullcalendar/daygrid/main.css";
import "@fullcalendar/timegrid/main.css";

export default function Edit({ attributes, setAttributes }) {
	const { location, service, worker } = attributes;
	const blockProps = useBlockProps();

	const [events, setEvents] = useState([]);
	const [locations, setLocations] = useState([]);
	const [services, setServices] = useState([]);
	const [workers, setWorkers] = useState([]);
	const calendarRef = useRef(null);

	useEffect(() => {
		fetchOptions("location", setLocations);
	}, []);

	useEffect(() => {
		if (location)
			fetchOptions("service", setServices, { location_id: location });
	}, [location]);

	useEffect(() => {
		if (service) fetchOptions("worker", setWorkers, { service_id: service });
	}, [service]);

	// useEffect(() => {
	// 	apiFetch({ path: "/wp/v2/eablocks/ea_appointments" })
	// 		.then((data) => setEvents(data))
	// 		.catch((err) => console.error("Error fetching appointments:", err));
	// }, []);

    useEffect(() => {
        if (!location || !service || !worker) return;
    
        const queryString = new URLSearchParams({
            location,
            service,
            worker,
        }).toString();
        console.log(queryString);
    
        apiFetch({ path: `/wp/v2/eablocks/ea_appointments?${queryString}` })
            .then((data) => {
                // Transform appointments to FullCalendar events
                const formatted = data.map((appointment) => ({
                    id: appointment.id,
                    title: appointment.status,
                    start: `${appointment.date}T${appointment.start}`,
                    end: `${appointment.date}T${appointment.end}`,
                    backgroundColor: getStatusColor(appointment.status),
                }));
                setEvents(formatted);
            })
            .catch((err) => console.error("Error fetching appointments:", err));
    }, [location, service, worker]);

	const fetchOptions = async (type, setState, params = {}) => {
		const queryString = new URLSearchParams(params).toString();
		try {
			const response = await apiFetch({
				path: `/wp/v2/eablocks/get_ea_options?type=${type}&${queryString}`,
			});
			setState(response);
		} catch (error) {
			console.error(`Error fetching ${type} options:`, error);
		}
	};

    const getStatusColor = (status) => {
        switch (status) {
            case "pending":
                return "#9b27b0";
            case "confirmed":
                return "#008000";
            case "reservation":
                return "#3f51b5";
            case "cancelled":
                return "#555";
            default:
                return "#999";
        }
    };
    

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody title={__("Block Settings", "ea-blocks")}>
					<SelectControl
						label={__("Location", "ea-blocks")}
						value={location}
						options={[{ label: "Select Location", value: "" }, ...locations]}
						onChange={(value) => {
							setAttributes({ location: value, service: "", worker: "" });
							setServices([]);
							setWorkers([]);
						}}
					/>
					<SelectControl
						label={__("Service", "ea-blocks")}
						value={service}
						options={[{ label: "Select Service", value: "" }, ...services]}
						onChange={(value) => setAttributes({ service: value, worker: "" })}
					/>
					<SelectControl
						label={__("Worker", "ea-blocks")}
						value={worker}
						options={[{ label: "Select Worker", value: "" }, ...workers]}
						onChange={(value) => setAttributes({ worker: value })}
					/>
				</PanelBody>
			</InspectorControls>

			<div
				style={{
					border: "1px solid #ccc",
					padding: "10px",
					background: "#fff",
					borderRadius: "6px",
				}}
			>
				<FullCalendar
					ref={calendarRef}
					plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
					initialView="dayGridMonth"
					headerToolbar={{
						left: "prev,next today",
						center: "title",
						right: "dayGridMonth,timeGridWeek,timeGridDay",
					}}
					editable={false}
					selectable={true}
					// events={[{ title: "Booking", date: "2025-04-16", backgroundColor: "#00aa00" }]} // Replace this with your dynamic data later
					events={events} // Replace this with your dynamic data later
					height="auto"
				/>

				<div style={{ marginTop: "15px" }}>
					<strong>Status</strong>{" "}
					<span
						style={{
							display: "inline-block",
							backgroundColor: "#9b27b0", // pending
							color: "#fff",
							padding: "4px 10px",
							marginRight: "5px",
							borderRadius: "4px",
							fontSize: "12px",
						}}
					>
						pending
					</span>
					<span
						style={{
							display: "inline-block",
							backgroundColor: "#008000", // confirmed
							color: "#fff",
							padding: "4px 10px",
							marginRight: "5px",
							borderRadius: "4px",
							fontSize: "12px",
						}}
					>
						confirmed
					</span>
					<span
						style={{
							display: "inline-block",
							backgroundColor: "#3f51b5", // reservation
							color: "#fff",
							padding: "4px 10px",
							marginRight: "5px",
							borderRadius: "4px",
							fontSize: "12px",
						}}
					>
						reservation
					</span>
					<span
						style={{
							display: "inline-block",
							backgroundColor: "#555", // cancelled
							color: "#fff",
							padding: "4px 10px",
							marginRight: "5px",
							borderRadius: "4px",
							fontSize: "12px",
						}}
					>
						cancelled
					</span>
				</div>
			</div>
			<style>{`
                /* Shrink FullCalendar header buttons */
.fc .fc-button {
	font-size: 12px;         /* Smaller text */
	padding: 2px 6px;        /* Smaller padding */
	min-width: auto;         /* Prevent default width if any */
	height: auto;            /* Adjust height */
	border-radius: 4px;      /* Optional: rounded buttons */
}

/* Reduce spacing between buttons */
.fc .fc-button-group > .fc-button {
	margin: 0 2px;
}

/* Optional: adjust title font size */
.fc .fc-toolbar-title {
	font-size: 18px;
}

            `}</style>
		</div>
	);
}
