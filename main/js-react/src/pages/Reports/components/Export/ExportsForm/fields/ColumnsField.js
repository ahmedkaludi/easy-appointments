import React from 'react';
import PropTypes from 'prop-types';

import { orderBy } from 'lodash';
import { useSnackbar } from 'notistack';
import { Tooltip, IconButton } from '@material-ui/core';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { __ } from '../../../../../../services/Localization';
import { Field, Autocomplete } from '../../../../../../ea-components';

const WEEK_DAYS = [
  { id: 0, value: 'Monday', label: __('Monday', 'easy-appointments') },
  { id: 1, value: 'Tuesday', label: __('Tuesday', 'easy-appointments') },
  { id: 2, value: 'Wednesday', label: __('Wednesday', 'easy-appointments') },
  { id: 3, value: 'Thursday', label: __('Thursday', 'easy-appointments') },
  { id: 4, value: 'Friday', label: __('Friday', 'easy-appointments') },
  { id: 5, value: 'Saturday', label: __('Saturday', 'easy-appointments') },
  { id: 6, value: 'Sunday', label: __('Sunday', 'easy-appointments') }
];

const Columns = ({ value, updateFieldValue, error }) => {
  const { enqueueSnackbar } = useSnackbar();

  const onChange = (e, newVal) => {
    const newDays = orderBy(newVal, ['id'], ['asc'])
      .map(val => val.value)
      .join(',');
    updateFieldValue(newDays);
  };

  const saveSettings = () => {
    enqueueSnackbar('Your columns settings are saved successfully!', {
      variant: 'success'
    });
  };

  const selected = value
    ? value.split(',').map(day => WEEK_DAYS.find(opt => opt.value === day))
    : [];

  return (
    <div className="d-flex align-items-center">
      <Autocomplete
        label={__('Columns *', 'easy-appointments')}
        placeholder={__('Column...', 'easy-appointments')}
        value={selected}
        onChange={onChange}
        options={WEEK_DAYS}
        error={error}
        customClass="flex-fill mr-3"
      />
      <Tooltip title="Save settings" arrow>
        <IconButton size="small" onClick={saveSettings}>
          <FontAwesomeIcon
            className="save-settings"
            icon={['fas', 'save']}
            size="lg"
          />
        </IconButton>
      </Tooltip>
    </div>
  );
};

Columns.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Columns.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const ColumnsField = () => (
  <Field name="columns" component={props => <Columns {...props} />} required />
);
