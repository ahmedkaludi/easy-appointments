import { useEffect, useRef, useState } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import "@fullcalendar/common/main.css";
import "@fullcalendar/daygrid/main.css";
import "@fullcalendar/timegrid/main.css";

export default function View({ attributes }) {
	const { location, service, worker } = attributes;
	const [events, setEvents] = useState([]);
	const calendarRef = useRef(null);

	useEffect(() => {
		if (!location || !service || !worker) return;

		const queryString = new URLSearchParams({
			location,
			service,
			worker,
		}).toString();

		apiFetch({ path: `/wp/v2/eablocks/ea_appointments?${queryString}` })
			.then((data) => {
				console.log(data)
				const formatted = data.map((appointment) => ({
					id: appointment.id,
					title: appointment.name,
					start: `${appointment.date}T${appointment.start}`,
					end: `${appointment.date}T${appointment.end}`,
					backgroundColor: getStatusColor(appointment.status),
				}));
				setEvents(formatted);
			})
			.catch((err) => console.error("Error fetching appointments:", err));
	}, [location, service, worker]);

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

	const handleEventClick = ({ event, el }) => {
		const title = event.title;
		const time = event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
	  
		alert(`${title} ${time}`); // replace with custom modal or tooltip
	};
	  

	return (
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
				selectable={false}
				events={events}
				contentHeight={600}
				eventDisplay="block"
				dayMaxEvents={true} // important for "+ more" link
				eventClick={handleEventClick} // optional: for modal or popover
			/>

			<div style={{ marginTop: "15px" }}>
				<strong>Status</strong>{" "}
				{[
					{ label: "pending", color: "#9b27b0" },
					{ label: "confirmed", color: "#008000" },
					{ label: "reservation", color: "#3f51b5" },
					{ label: "cancelled", color: "#555" },
				].map(({ label, color }) => (
					<span
						key={label}
						style={{
							display: "inline-block",
							backgroundColor: color,
							color: "#fff",
							padding: "4px 10px",
							marginRight: "5px",
							borderRadius: "4px",
							fontSize: "12px",
						}}
					>
						{label}
					</span>
				))}
			</div>
			<style>{`
                /* Base button styling */
/* FullCalendar Button Base Style */
.fc .fc-button {
	font-size: 14px;
	font-weight: 500;
	padding: 4px 12px;
	height: 36px;
	min-width: 36px;
	border-radius: 4px;
	border: 1px solid #ccc;
	background-color: #ffffff;
	color: #333;
	box-shadow: none;
	transition: all 0.2s ease;
}

/* Hover State */
.fc .fc-button:hover {
	background-color: #f5f5f5;
	border-color: #bbb;
}

/* Active Button (e.g., "Month") */
.fc .fc-button.fc-button-active {
	background-color: #1976d2;
	color: white;
	border-color: #1976d2;
}

/* Disabled Button (e.g., "Today" when inactive) */
.fc .fc-button:disabled {
	background-color: #f1f1f1;
	color: #aaa;
	border-color: #ddd;
	cursor: not-allowed;
}

/* Reduce space between buttons */
.fc .fc-button-group > .fc-button {
	margin: 0 4px;
}

/* Adjust title size */
.fc .fc-toolbar-title {
	font-size: 22px;
	font-weight: 600;
	color: #222;
}




            `}</style>
		</div>
	);
}
