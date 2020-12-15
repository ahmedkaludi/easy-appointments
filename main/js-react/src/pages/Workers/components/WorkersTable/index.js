import React from 'react';
import PropTypes from 'prop-types';

import { ContentBox, BasicTable } from '../../../../ea-components';

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

export const WorkersTable = ({ data, onEdit, onDelete, processing }) => {
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
      <BasicTable data={adaptedData} config={WORKERS_CONFIG} />
    </ContentBox>
  );
};

WorkersTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  processing: PropTypes.string
};

WorkersTable.defaultProps = {
  data: [],
  disabled: null
};
