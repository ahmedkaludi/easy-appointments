import React from 'react';
import PropTypes from 'prop-types';

import { renderCell } from './TableCell';

/**
 * Generating table row from config obj
 * ex. [{ text: 'Text', position: 'left', style: { width: 180 }}]
 */
export const TableRow = ({ config, data }) => {
  const generateTds = () =>
    Object.keys(config).map(key => renderCell(config[key], data[key]));
  // <td sclassName="font-weight-bold">{data.title}</td>;
  // };

  return <tr>{generateTds()}</tr>;
};

TableRow.propTypes = {
  config: PropTypes.objectOf(PropTypes.any),
  data: PropTypes.objectOf(PropTypes.any)
};

TableRow.defaultProps = {
  config: {},
  data: {}
};
