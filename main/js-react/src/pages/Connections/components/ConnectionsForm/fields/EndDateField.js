import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, DatePicker } from '../../../../../ea-components';

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

const add50Years = date => {
  const d = new Date(date);
  d.setFullYear(d.getFullYear() + 50);
  return d;
};

const EndDate = ({ value, updateFieldValue, error, model }) => {
  const fromDate = model.day_from;
  const isUnlimited = model.is_unlimited;

  useEffect(() => {
    if (isUnlimited === '1' && fromDate) {
      const futureDate = add50Years(new Date(fromDate));
      updateFieldValue(formatDate(futureDate));
      return;
    }

    if (!fromDate) {
      return;
    }

    if (startGreaterThanEnd(fromDate, value)) {
      const end = getNextDay(new Date(fromDate));
      updateFieldValue(formatDate(end));
    }
  }, [fromDate, isUnlimited]);

  const onChange = date => updateFieldValue(formatDate(date));

  const minDate = fromDate ? { minDate: new Date(fromDate) } : {};

  return (
    <DatePicker
      label={__('End date *', 'easy-appointments')}
      value={value}
      onChange={onChange}
      error={error}
      adornment
      disablePast={false}
      disabled={isUnlimited === '1'} // 👈 disable manual edit
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

export const EndDateField = () => (
  <Field name="day_to" component={props => <EndDate {...props} />} required />
);
