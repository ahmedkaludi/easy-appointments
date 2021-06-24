import React from 'react';

import Chip from '@material-ui/core/Chip';
import Tooltip from '@material-ui/core/Tooltip';
import FaceIcon from '@material-ui/icons/Face';
import PinDropIcon from '@material-ui/icons/PinDrop';
import BuildIcon from '@material-ui/icons/Build';

const KEYS = Object.freeze({
  LOCATION: 'location',
  SERVICE: 'service',
  WORKER: 'worker'
});

const ICONS = Object.freeze({
  [KEYS.LOCATION]: <PinDropIcon />,
  [KEYS.SERVICE]: <BuildIcon />,
  [KEYS.WORKER]: <FaceIcon />
});

export const FilterChips = ({ filters, onChange, disabled }) => {
  const handleDelete = key =>
    onChange(prev => {
      const newVal = { ...prev };
      delete newVal[key];
      return newVal;
    });

  const renderChip = (key, obj) => (
    <Tooltip title={`${key.charAt(0).toUpperCase() + key.slice(1)}`}>
      <Chip
        variant="outlined"
        color="primary"
        label={filters[key].label}
        onDelete={() => handleDelete(key)}
        icon={ICONS[key]}
        size="small"
        className="m-1"
        disabled={disabled}
      />
    </Tooltip>
  );
  return (
    <div className="filter-chips">
      {!!filters[KEYS.LOCATION] && renderChip(KEYS.LOCATION)}
      {!!filters[KEYS.SERVICE] && renderChip(KEYS.SERVICE)}
      {!!filters[KEYS.WORKER] && renderChip(KEYS.WORKER)}
    </div>
  );
};
