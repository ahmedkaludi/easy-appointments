import React from 'react';
import PropTypes from 'prop-types';

/**
 * Generating table header from config arr
 * ex. [{ text: 'Text', position: 'left', style: { width: 180 }}]
 */
export const TableHeader = ({ config }) => {
  const generateTh = item => {
    return (
      <th className={`text-${item.position}`} style={{ minWidth: '200px' }}>
        {item.header}
      </th>
    );
  };

  return config ? (
    <thead>
      <tr>{config.map(item => generateTh(item))}</tr>
    </thead>
  ) : null;
};

TableHeader.propTypes = {
  config: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
};

TableHeader.defaultProps = {
  config: null
};
