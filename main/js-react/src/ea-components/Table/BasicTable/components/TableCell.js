import React from 'react';

const CELL_TYPES = {
  TEXT: 'text'
};

/**
 * ex. config {type: 'text', className: 'some class'}
 */
const TextCell = (config, data) => {
  const { cellClass, position } = config;

  return <td className={`text-${position} ${cellClass ?? ''}`}>{data}</td>;
};

export const renderCell = (config, data) => {
  switch (config.type) {
    case CELL_TYPES.TEXT:
      return TextCell(config, data);
    default:
      return null;
  }
};
