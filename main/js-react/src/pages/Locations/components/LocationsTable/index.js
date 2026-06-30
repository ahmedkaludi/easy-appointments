import React, { useMemo, useState } from 'react';
import PropTypes from 'prop-types';

import { __, _x } from '../../../../services/Localization';
import { SortCommunicator } from '../../../../communicators';
import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';

const BASE_CONFIG = {
  id: {
    header: __('Id', 'easy-appointments'),
    headerStyle: { maxWidth: '50px' },
    position: 'left',
    type: 'text'
  },
  name: {
    header: _x('Name', 'location', 'easy-appointment'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  address: {
    header: __('Address', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  location: {
    header: __('Location', 'easy-appointments'),
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
  { value: 'name', label: _x('Name', 'location', 'easy-appointments') },
  { value: 'address', label: __('Address', 'easy-appointments') },
  { value: 'location', label: __('Location', 'easy-appointments') }
];

const SEARCHABLE_COLUMNS = ['id', 'name', 'address', 'location'];

export const LocationsTable = ({
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

  const CONFIG = {
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
    ...BASE_CONFIG
  };
  const adaptedData = filteredData.map(record => ({
    ...record,
    select: {
      id: record.id
    },
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
      <TableSorter
        columns={COLUMNS}
        sortingFunc={SortCommunicator.saveSortLocations}
        onSortingDone={onSort}>
        <input
          type="text"
          className="form-control ea-locations-search ml-2"
          placeholder={__(
            'Search by name, address or location',
            'easy-appointments'
          )}
          value={searchTerm}
          onChange={event => setSearchTerm(event.target.value)}
        />
      </TableSorter>
      <BasicTable data={adaptedData} config={CONFIG} />
    </ContentBox>
  );
};

LocationsTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  onSort: PropTypes.func.isRequired,
  processing: PropTypes.string,
  selectedIds: PropTypes.array.isRequired,
  toggleSelect: PropTypes.func.isRequired
};

LocationsTable.defaultProps = {
  data: [],
  disabled: null
};
