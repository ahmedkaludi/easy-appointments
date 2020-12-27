import React from 'react';
import PropTypes from 'prop-types';

import { __, _x } from '../../../../services/Localization';
import { SortCommunicator } from '../../../../communicators';
import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';

const LOCATIONS_CONFIG = {
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
  { value: 'name', label: _x('Name', 'location', 'easy-appointments') },
  { value: 'address', label: __('Address', 'easy-appointments') },
  { value: 'location', label: __('Location', 'easy-appointments') }
];

export const LocationsTable = ({
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
        onSortingDone={onSort}
      />
      <BasicTable data={adaptedData} config={LOCATIONS_CONFIG} />
    </ContentBox>
  );
};

LocationsTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  onSort: PropTypes.func.isRequired,
  processing: PropTypes.string
};

LocationsTable.defaultProps = {
  data: [],
  disabled: null
};
