import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../services/Localization';
import { ContentBox, BasicTable } from '../../../../ea-components';

const VACATION_CONFIG = {
  title: {
    header: __('Title', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  tooltip: {
    header: __('Tooltip', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  workers: {
    header: __('Workers', 'easy-appointments'),
    position: 'center',
    type: 'avatar'
  },
  days: {
    header: __('Dates', 'easy-appointments'),
    position: 'center',
    type: 'chips'
  },
  actions: {
    header: __('Actions', 'easy-appointments'),
    position: 'center',
    type: 'actions'
  }
};

export const VacationTable = ({ data, onEdit, onDelete, processing }) => {
  const adaptedData = data.map(record => ({
    ...record,
    workers: record.workers.map(worker => worker.name),
    actions: [
      {
        tooltip: __('Edit', 'easy-appointments'),
        className: 'text-success',
        icon: 'edit',
        action: () => onEdit(record)
      },
      {
        tooltip: __('Delete', 'easy-appointments'),
        className: 'text-danger',
        icon: processing === record.id ? 'processing' : 'delete',
        action: () => onDelete(record)
      }
    ]
  }));

  return (
    <ContentBox>
      {processing && <div className="ea-transparent-mask" />}
      <BasicTable data={adaptedData} config={VACATION_CONFIG} />
    </ContentBox>
  );
};

VacationTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  processing: PropTypes.string
};

VacationTable.defaultProps = {
  data: [],
  disabled: null
};
