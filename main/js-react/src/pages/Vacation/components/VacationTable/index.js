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
  workers: { header: 'Workers', position: 'center', type: 'avatar' },
  dates: { header: 'Dates', position: 'center' },
  actions: { header: 'Actions', position: 'center' }
};

export const VacationTable = ({ data }) => {
  const adaptedData = data.map(record => ({
    ...record,
    workers: record.workers.map(worker => worker.name)
  }));

  return (
    <ContentBox>
      <BasicTable data={adaptedData} config={VACATION_CONFIG} />
    </ContentBox>
  );
};

VacationTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
};

VacationTable.defaultProps = {
  data: []
};
