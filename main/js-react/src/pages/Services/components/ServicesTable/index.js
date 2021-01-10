import React from 'react';
import PropTypes from 'prop-types';

import { __, _x } from '../../../../services/Localization';
import { SortCommunicator } from '../../../../communicators';
import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';

const SERVICES_CONFIG = {
  id: {
    header: __('Id', 'easy-appointments'),
    headerStyle: { maxWidth: '50px' },
    position: 'left',
    type: 'text'
  },
  name: {
    header: _x('Name', 'service', 'easy-appointments'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text',
    cellClass: 'font-weight-bold'
  },
  duration: {
    header: __('Duration *', 'easy-appointments'),
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  slot_step: {
    header: __('Slot step *', 'easy-appointments'),
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  block_before: {
    header: __('Block before *', 'easy-appointments'),
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  block_after: {
    header: __('Block after *', 'easy-appointments'),
    headerStyle: { minWidth: '150px' },
    position: 'left',
    type: 'text'
  },
  price: {
    header: __('Price', 'easy-appointments'),
    headerStyle: { minWidth: '150px' },
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
  { value: 'name', label: _x('Name', 'service', 'easy-appointments') },
  { value: 'duration', label: __('Duration', 'easy-appointments') },
  { value: 'slot_step', label: __('Slot step', 'easy-appointments') },
  { value: 'block_before', label: __('Block before', 'easy-appointments') },
  { value: 'block_after', label: __('Block after', 'easy-appointments') },
  { value: 'price', label: __('Price', 'easy-appointments') }
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
        sortingFunc={SortCommunicator.saveSortServices}
        onSortingDone={onSort}
        hint={__('* value in minutes', 'easy-appointments')}
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
