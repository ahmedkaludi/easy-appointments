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

export const VacationTable = ({ data, onEdit, onDelete }) => {
  const adaptedData = data.map(record => ({
    ...record,
    workers: record.workers.map(worker => worker.name),
    actions: [
      {
        tooltip: 'Edit',
        className: 'text-success',
        icon: 'edit',
        action: () => onEdit(record)
      },
      {
        tooltip: 'Delete',
        className: 'text-danger',
        icon: 'delete',
        action: () => onDelete(record)
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
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired
};

VacationTable.defaultProps = {
  data: []
};
