import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable } from '../../../../ea-components';

const VACATION_CONFIG = {
  title: {
    header: 'Title',
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  tooltip: { header: 'Tooltip', position: 'left', type: 'text' },
  workers: { header: 'Workers', position: 'center' },
  dates: { header: 'Dates', position: 'center' },
  actions: { header: 'Actions', position: 'center' }
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
