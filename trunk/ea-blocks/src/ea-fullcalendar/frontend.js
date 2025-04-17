import { createRoot } from "@wordpress/element";
import View from "./view";

document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("ea-fullcalendar-app");
	if (container && window.eaFullCalendarData) {
		const root = createRoot(container);
		root.render(<View attributes={window.eaFullCalendarData} />);
	}
});
