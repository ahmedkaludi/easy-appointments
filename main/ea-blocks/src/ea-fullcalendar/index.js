/**
 * Registers a new block provided a unique name and an object defining its behavior.
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Styles
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';
import metadata from './block.json';

// ✅ Add this to ensure view.js is bundled and enqueued for the front-end
import './view';

registerBlockType(metadata.name, {
	edit: Edit,
	save,
});
