import React from 'react';
import PropTypes from 'prop-types';

// import { __, _x } from '../../../../services/Localization';
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
    header: __('Location / Service / Worker', 'location', 'easy-appointment'),
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
    header: __('Time', 'easy-appointments'),
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

export const ConnectionsTable = ({
  data,
  onEdit,
  onDelete,
  onClone,
  processing
}) => {
  const adaptedData = data.map(record => ({
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

  return (
    <ContentBox>
      {processing && <div className="ea-transparent-mask" />}
      <BasicTable data={adaptedData} config={CONNECTIONS_CONFIG} />
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
