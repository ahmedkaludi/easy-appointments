import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../services/Localization';
import Field from '../../../Form/Field';
import DatePicker from '../../DatePicker';

const formatDate = date => {
  date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
  return date.toJSON().slice(0, 10);
};

const getNextDay = date => {
  const tomorrow = new Date(date);
  tomorrow.setDate(date.getDate() + 1);
  return tomorrow;
};

const startGreaterThanEnd = (a, b) => {
  if (!b) {
    return true;
  }

  const dateA = a.split('-').join('');
  const dateB = b.split('-').join('');
  return !!(dateA > dateB);
};

const EndDate = ({ value, updateFieldValue, error, model, startName }) => {
  const fromDate = model[startName];

  useEffect(() => {
    if (!fromDate) {
      return;
    }
    if (startGreaterThanEnd(fromDate, value)) {
      const end = getNextDay(new Date(fromDate));
      updateFieldValue(formatDate(end));
    }
  }, [fromDate]);

  const onChange = date => updateFieldValue(formatDate(date));

  const minDate = fromDate ? { minDate: new Date(fromDate) } : {};

  return (
    <DatePicker
      label={__('End date *', 'easy-appointments')}
      value={value}
      onChange={onChange}
      error={error}
      disablePast={false}
      adornment
      {...minDate}
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

export const EndDateField = ({ name, startName }) => (
  <Field
    name={name}
    component={props => <EndDate {...props} startName={startName} />}
    required
  />
);
