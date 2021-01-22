import React from 'react';
import PropTypes from 'prop-types';

import { orderBy } from 'lodash';
import { __ } from '../../../../../services/Localization';
import { Field, Autocomplete } from '../../../../../ea-components';

const WEEK_DAYS = [
  { id: 0, value: 'Monday', label: 'Monday' },
  { id: 1, value: 'Tuesday', label: 'Tuesday' },
  { id: 2, value: 'Wednesday', label: 'Wednesday' },
  { id: 3, value: 'Thursday', label: 'Thursday' },
  { id: 4, value: 'Friday', label: 'Friday' },
  { id: 5, value: 'Saturday', label: 'Saturday' },
  { id: 6, value: 'Sunday', label: 'Sunday' }
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
