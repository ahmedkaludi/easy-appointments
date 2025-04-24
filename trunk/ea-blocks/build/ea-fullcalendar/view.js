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
		<div style={{ border: "1px solid #ccc", padding: "10px", background: "#fff", borderRadius: "6px" }}>
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
				height="auto"
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
		</div>
	);
}
