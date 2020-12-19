import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable, TableSorter } from '../../../../ea-components';

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

export const ServicesTable = ({ data, onEdit, onDelete, processing }) => {
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

  const tableHeader = (
    <div className="px-3 py-4 d-flex justify-content-between align-items-center">
      <TableSorter columns={[]} sortingFunc={f => f} onSortingDone={f => f} />
      <span className="text-ea font-size-xs">* value in minutes</span>
    </div>
  );

  return (
    <ContentBox>
      {processing && <div className="ea-transparent-mask" />}
      {tableHeader}
      <BasicTable data={adaptedData} config={SERVICES_CONFIG} />
    </ContentBox>
  );
};

ServicesTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  processing: PropTypes.string
};

ServicesTable.defaultProps = {
  data: [],
  disabled: null
};
