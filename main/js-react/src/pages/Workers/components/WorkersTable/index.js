import React, { useMemo, useState } from 'react';
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

const SEARCHABLE_COLUMNS = ['id', 'name', 'description', 'email', 'phone'];

export const WorkersTable = ({
  data,
  onEdit,
  onDelete,
  onSort,
  processing,
  selectedIds,
  toggleSelect
}) => {
  const [searchTerm, setSearchTerm] = useState('');
  const normalizedSearchTerm = searchTerm.trim().toLowerCase();

  const filteredData = useMemo(() => {
    if (!normalizedSearchTerm) {
      return data;
    }

    return data.filter(record =>
      SEARCHABLE_COLUMNS.some(column =>
        String(record[column] || '')
          .toLowerCase()
          .includes(normalizedSearchTerm)
      )
    );
  }, [data, normalizedSearchTerm]);

  const visibleSelectedIds = selectedIds.filter(id =>
    filteredData.some(item => item.id === id)
  );
  const allSelected =
    visibleSelectedIds.length === filteredData.length &&
    filteredData.length > 0;
  const dynamicConfig = {
    select: {
      header: (
        <input
          type="checkbox"
          checked={allSelected}
          onChange={e => {
            if (e.target.checked) {
              const allIds = filteredData.map(item => item.id);
              toggleSelect('ALL', allIds);
            } else {
              toggleSelect('NONE', []);
            }
          }}
        />
      ),
      type: 'component',
      position: 'left',
      render: row => (
        <input
          type="checkbox"
          checked={selectedIds.includes(row.id)}
          onChange={() => toggleSelect(row.id)}
        />
      )
    },
    ...WORKERS_CONFIG
  };

  const adaptedData = filteredData.map(record => ({
    ...record,
    select: { id: record.id },
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
        onSortingDone={onSort}>
        <input
          type="text"
          className="form-control ea-workers-search ml-2"
          placeholder={__(
            'Search by name, email, phone or description',
            'easy-appointments'
          )}
          value={searchTerm}
          onChange={event => setSearchTerm(event.target.value)}
        />
      </TableSorter>
      <BasicTable data={adaptedData} config={dynamicConfig} />
    </ContentBox>
  );
};

WorkersTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  onSort: PropTypes.func.isRequired,
  processing: PropTypes.string,
  selectedIds: PropTypes.array.isRequired,
  toggleSelect: PropTypes.func.isRequired
};

WorkersTable.defaultProps = {
  data: [],
  disabled: null
};
