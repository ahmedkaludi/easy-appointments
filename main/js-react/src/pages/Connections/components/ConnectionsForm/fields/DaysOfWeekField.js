import React from 'react';
import PropTypes from 'prop-types';

import { orderBy } from 'lodash';
import { __ } from '../../../../../services/Localization';
import { Field, Autocomplete } from '../../../../../ea-components';

const WEEK_DAYS = [
  { id: 0, value: 'Monday', label: __('Monday', 'easy-appointments') },
  { id: 1, value: 'Tuesday', label: __('Tuesday', 'easy-appointments') },
  { id: 2, value: 'Wednesday', label: __('Wednesday', 'easy-appointments') },
  { id: 3, value: 'Thursday', label: __('Thursday', 'easy-appointments') },
  { id: 4, value: 'Friday', label: __('Friday', 'easy-appointments') },
  { id: 5, value: 'Saturday', label: __('Saturday', 'easy-appointments') },
  { id: 6, value: 'Sunday', label: __('Sunday', 'easy-appointments') }
];

const DaysOfWeek = ({ value, updateFieldValue, error }) => {
  const onChange = (e, newVal) => {
    const newDays = orderBy(newVal, ['id'], ['asc'])
      .map(val => val.value)
      .join(',');
    updateFieldValue(newDays);
  };

  const selected = value
    ? value.split(',').map(day => WEEK_DAYS.find(opt => opt.value === day))
    : [];

  return (
    <Autocomplete
      label={__('Days of week *', 'easy-appointments')}
      placeholder={__('Day...', 'easy-appointments')}
      value={selected}
      onChange={onChange}
      options={WEEK_DAYS}
      error={error}
    />
  );
};

DaysOfWeek.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

DaysOfWeek.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const DaysOfWeekField = () => (
  <Field
    name="day_of_week"
    component={props => <DaysOfWeek {...props} />}
    required
  />
);
