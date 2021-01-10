import React from 'react';
import PropTypes from 'prop-types';

import { __, _x } from '../../../../services/Localization';
import { SortCommunicator } from '../../../../communicators';
import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';

const WORKERS_CONFIG = {
  id: {
    header: __('Id', 'easy-appointments'),
    headerStyle: { maxWidth: '50px' },
    position: 'left',
    type: 'text'
  },
  name: {
    header: _x('Name', 'worker', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  description: {
    header: __('Description', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  email: {
    header: __('Email', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  phone: {
    header: __('Phone', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  actions: {
    header: __('Actions', 'easy-appointments'),
    position: 'center',
    type: 'actions'
  }
};

const COLUMNS = [
  { value: 'id', label: _x('Id', 'id', 'easy-appointments') },
  { value: 'name', label: _x('Name', 'worker', 'easy-appointments') },
  { value: 'description', label: __('Description', 'easy-appointments') },
  { value: 'email', label: __('Email', 'easy-appointments') },
  { value: 'phone', label: __('Phone', 'easy-appointments') }
];

export const WorkersTable = ({
  data,
  onEdit,
  onDelete,
  onSort,
  processing
}) => {
  const adaptedData = data.map(record => ({
    ...record,
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
        icon: processing === record.id ? 'processing' : 'delete',
        action: () => onDelete(record)
      }
    ]
  }));

  return (
    <ContentBox>
      {processing && <div className="ea-transparent-mask" />}
      <TableSorter
        columns={COLUMNS}
        sortingFunc={SortCommunicator.saveSortWorkers}
        onSortingDone={onSort}
      />
      <BasicTable data={adaptedData} config={WORKERS_CONFIG} />
    </ContentBox>
  );
};

WorkersTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  onSort: PropTypes.func.isRequired,
  processing: PropTypes.string
};

WorkersTable.defaultProps = {
  data: [],
  disabled: null
};
