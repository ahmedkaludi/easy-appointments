import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, DatePicker } from '../../../../../ea-components';

const formatDate = date => {
  date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
  return date.toJSON().slice(0, 10);
};

const getNextDay = date => {
  const tomorrow = new Date();
  tomorrow.setDate(date.getDate() + 1);
  return tomorrow;
};

const EndDate = ({ value, updateFieldValue, error }) => {
  useEffect(() => {
    if (!value) {
      const end = getNextDay(new Date());
      updateFieldValue(formatDate(end));
    }
  }, [value]);

  const onChange = date => updateFieldValue(date);

  return (
    <DatePicker
      label={__('End date *', 'easy-appointments')}
      value={value}
      onChange={onChange}
      error={error}
    />
  );
};

EndDate.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

EndDate.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const EndDateField = () => (
  <Field name="day_to" component={props => <EndDate {...props} />} required />
);
