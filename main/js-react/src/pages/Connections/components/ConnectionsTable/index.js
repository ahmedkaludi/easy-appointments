import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../services/Localization';
import { ContentBox, BasicTable } from '../../../../ea-components';
import { DataService } from '../../../../services';

const LOCATIONS = DataService.get('Locations');
const SERVICES = DataService.get('Services');
const WORKERS = DataService.get('Workers');

const CONNECTIONS_CONFIG = {
  id: {
    header: __('Id', 'easy-appointments'),
    headerStyle: { maxWidth: '50px' },
    position: 'left',
    type: 'text'
  },
  connection: {
    header: __('Location / Service / Employee', 'location', 'easy-appointment'),
    headerStyle: { minWidth: '200px' },
    position: 'left',
    type: 'text'
  },
  slot_count: {
    header: __('Slots', 'easy-appointments'),
    headerStyle: { maxWidth: '50px' },
    position: 'left',
    type: 'text'
  },
  day_of_week: {
    header: __('Days of week', 'easy-appointments'),
    position: 'center',
    type: 'chips'
  },
  time: {
    header: __('Working Hours', 'easy-appointments'),
    headerStyle: { maxWidth: '100px' },
    position: 'left',
    type: 'text_with_label'
  },
  date: {
    header: __('Date range', 'easy-appointments'),
    headerStyle: { maxWidth: '100px' },
    position: 'left',
    type: 'text_with_label'
  },
  is_working: {
    header: __('Is working', 'easy-appointments'),
    headerStyle: { maxWidth: '100px' },
    position: 'left',
    type: 'text'
  },
  actions: {
    header: __('Actions', 'easy-appointments'),
    position: 'center',
    type: 'actions'
  }
};

const TableLegend = () => (
  <div className="p-3 d-flex justify-content-end">
    <div className="connections-legend d-flex align-items-center">
      <div className="d-flex align-items-center">
        <span className="p-2 ea-not-active rounded-circle" />
        <span className="font-size-xs pl-1 ea-table-cell-label">Inactive</span>
      </div>
      <div className="d-flex align-items-center ml-3">
        <span className="p-2 ea-working rounded-circle" />
        <span className="font-size-xs pl-1 ea-table-cell-label">Working</span>
      </div>
      <div className="d-flex align-items-center ml-3">
        <span className="p-2 ea-not-working rounded-circle" />
        <span className="font-size-xs pl-1 ea-table-cell-label">
          Not working
        </span>
      </div>
    </div>
  </div>
);

export const ConnectionsTable = ({
  data,
  onEdit,
  onDelete,
  onClone,
  processing
}) => {
  // maybe we have connections for assets that are not longer there
  const cleanData = data.filter(
    record =>
      LOCATIONS.some(loc => loc.id === record.location) &&
      SERVICES.some(ser => ser.id === record.service) &&
      WORKERS.some(work => work.id === record.worker)
  );

  const adaptedData = cleanData.map(record => ({
    ...record,
    connection: [
      LOCATIONS.find(loc => loc.id === record.location).name,
      SERVICES.find(ser => ser.id === record.service).name,
      WORKERS.find(work => work.id === record.worker).name
    ],
    day_of_week: record.day_of_week.split(','),
    date: [
      { label: 'Active from', text: record.day_from },
      { label: 'to', text: record.day_to }
    ],
    time: [
      { label: 'Starts at', text: record.time_from },
      { label: 'Ends at', text: record.time_to }
    ],
    is_working: record.is_working === '1' ? 'Yes' : 'No',
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
      },
      {
        tooltip: __('Clone', 'easy-appointments'),
        className: 'text-info',
        icon: 'clone',
        action: () => onClone(record)
      }
    ]
  }));

  const applyClassToRow = ({ date, is_working }) => {
    const currentDate = new Date().toISOString().slice(0, 10);
    const formatClassName = name => ({ className: name });

    // case when we don't have active connection
    if (currentDate < date[0].text || currentDate > date[1].text) {
      return formatClassName('ea-not-active');
    }

    if (is_working === 'Yes') {
      return formatClassName('ea-working');
    }

    return formatClassName('ea-not-working');
  };

  return (
    <ContentBox>
      {processing && <div className="ea-transparent-mask" />}
      <TableLegend />
      <BasicTable
        data={adaptedData}
        config={CONNECTIONS_CONFIG}
        rowCallback={applyClassToRow}
      />
    </ContentBox>
  );
};

ConnectionsTable.propTypes = {
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onEdit: PropTypes.func.isRequired,
  onDelete: PropTypes.func.isRequired,
  onClone: PropTypes.func.isRequired,
  processing: PropTypes.string
};

ConnectionsTable.defaultProps = {
  data: [],
  processing: null
};
