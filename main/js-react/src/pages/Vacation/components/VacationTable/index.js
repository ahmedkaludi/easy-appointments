import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable } from '../../../../ea-components';

const VACATION_CONFIG = {
  headers: [
    { text: 'Title', position: 'left' },
    { text: 'Tooltip', position: 'left' },
    { text: 'Workers', position: 'center' },
    { text: 'Dates', position: 'center' },
    { text: 'Actions', position: 'center' }
  ],
  cells: {
    name: { type: 'text', class: 'font-weight-bold' },
    tooltip: { type: 'text', class: 'font-weight-bold' }
  }
};

export const VacationTable = ({ data }) => (
  <ContentBox>
    <BasicTable data={data} config={VACATION_CONFIG} />
  </ContentBox>
);

VacationTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
};

VacationTable.defaultProps = {
  data: null
};
