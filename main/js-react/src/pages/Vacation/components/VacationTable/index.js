import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable } from '../../../../ea-components';

const TABLE_HEADER = [
  { text: 'Name', position: 'left' },
  { text: 'Tooltip', position: 'left' },
  { text: 'Workers', position: 'center' },
  { text: 'Dates', position: 'center' },
  { text: 'Actions', position: 'center' }
];

export const VacationTable = ({ data }) => (
  <ContentBox>
    <BasicTable data={data} headers={TABLE_HEADER} />
  </ContentBox>
);

VacationTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
};

VacationTable.defaultProps = {
  data: null
};
