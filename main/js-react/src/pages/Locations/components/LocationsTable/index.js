import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';
import { SortCommunicator } from '../../../../communicators';

const LOCATIONS_CONFIG = {
  name: {
    header: 'Name',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  address: {
    header: 'Address',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  location: {
    header: 'Location',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  actions: { header: 'Actions', position: 'center', type: 'actions' }
};

const COLUMNS = [
  { value: 'name', label: 'Name' },
  { value: 'address', label: 'Address' },
  { value: 'location', label: 'Location' }
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
