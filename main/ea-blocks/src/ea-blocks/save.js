export default function save({ attributes }) {
	const {
		width,
		scrollOff,
		layoutCols,
		location,
		service,
		worker,
		defaultDate,
	} = attributes;

	return (
		<p>
			<code>
				[ea_bootstrap
				{width ? ` width="${width}"` : ""}
				{scrollOff ? ` scroll_off="true"` : ""}
				{layoutCols ? ` layout_cols="${layoutCols}"` : ""}
				{location ? ` location="${location}"` : ""}
				{service ? ` service="${service}"` : ""}
				{worker ? ` worker="${worker}"` : ""}
				{defaultDate ? ` default_date="${defaultDate}"` : ""}]
			</code>
		</p>
	);
}
