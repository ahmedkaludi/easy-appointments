import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';
import { SortCommunicator } from '../../../../communicators';

const WORKERS_CONFIG = {
  name: {
    header: 'Name',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  description: {
    header: 'Description',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  email: {
    header: 'Email',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  phone: {
    header: 'Phone',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  actions: { header: 'Actions', position: 'center', type: 'actions' }
};

const COLUMNS = [
  { value: 'name', label: 'Name' },
  { value: 'description', label: 'Description' },
  { value: 'email', label: 'Email' },
  { value: 'phone', label: 'Phone' }
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
