import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';
import { SortCommunicator } from '../../../../communicators';

const SERVICES_CONFIG = {
  name: {
    header: 'Name',
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  duration: {
    header: 'Duration *',
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  slot_step: {
    header: 'Slot step *',
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  block_before: {
    header: 'Block before *',
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  block_after: {
    header: 'Block after *',
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  price: {
    header: 'Price',
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  actions: { header: 'Actions', position: 'center', type: 'actions' }
};

const COLUMNS = [
  { value: 'name', label: 'Name' },
  { value: 'duration', label: 'Duration' },
  { value: 'slot_step', label: 'Slot step' },
  { value: 'block_before', label: 'Block before' },
  { value: 'block_after', label: 'Block after' },
  { value: 'price', label: 'Price' }
];

export const ServicesTable = ({
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
        sortingFunc={SortCommunicator.saveSortServices}
        onSortingDone={onSort}
        hint="* value in minutes"
      />
      <BasicTable data={adaptedData} config={SERVICES_CONFIG} />
    </ContentBox>
  );
};

ServicesTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  onSort: PropTypes.func.isRequired,
  processing: PropTypes.string
};

ServicesTable.defaultProps = {
  data: [],
  disabled: null
};
