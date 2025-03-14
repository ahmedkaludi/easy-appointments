import React from 'react';
import PropTypes from 'prop-types';

import { renderCell } from './TableCell';

export const TableRow = ({ config, data, ...props }) => {
  const generateTds = () =>
    Object.keys(config).map(key => renderCell(config[key], data[key]));

  return (
    <tr
      className="sortable-handle"
      row-data={JSON.stringify(data)}
      data-id={data.id}
      {...props}>
      {generateTds()}
    </tr>
  );
};

TableRow.propTypes = {
  config: PropTypes.objectOf(PropTypes.any),
  data: PropTypes.objectOf(PropTypes.any)
};

TableRow.defaultProps = {
  config: {},
  data: {}
};
