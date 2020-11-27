import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable } from '../../../../ea-components';

const VACATION_CONFIG = {
  title: {
    header: 'Title',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  tooltip: {
    header: 'Tooltip',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  workers: { header: 'Workers', position: 'center', type: 'avatar' },
  days: { header: 'Dates', position: 'center', type: 'chips' },
  actions: { header: 'Actions', position: 'center', type: 'actions' }
};

export const VacationTable = ({ data }) => {
  const adaptedData = data.map(record => ({
    ...record,
    workers: record.workers.map(worker => worker.name),
    actions: [
      {
        tooltip: 'Edit',
        className: 'text-success',
        icon: 'edit',
        action: f => f
      },
      {
        tooltip: 'Delete',
        className: 'text-danger',
        icon: 'delete',
        action: f => f
      }
    ]
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
