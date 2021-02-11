import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, TimePicker } from '../../../../../ea-components';

const dateToTime = date => {
  if (!date) {
    return null;
  }

  const datetext = date.toTimeString();
  return datetext.split(' ')[0];
};

const timeToDate = time => {
  if (!time) {
    return null;
  }

  const timetext = time.split(':');
  const date = new Date();
  date.setHours(timetext[0], timetext[1], timetext[2]);
  return date;
};

const EndTime = ({ value, updateFieldValue, model, error, setFieldErrors }) => {
  const handleErrors = () => {
    const end = value.split(':').join();
    const start = model['time_from'].split(':').join();

    setFieldErrors('time_to', end < start ? 'Must be after start time!' : null);
    setFieldErrors(
      'time_from',
      end < start ? 'Must be before end time!' : null
    );
  };

  useEffect(() => {
    if (!value) {
      updateFieldValue('23:59:59');
      return;
    }

    handleErrors();
  }, [value]);

  const onChange = date => updateFieldValue(dateToTime(date));

  return (
    <TimePicker
      label={__('End time *', 'easy-appointments')}
      value={timeToDate(value)}
      onChange={onChange}
      error={error}
      adornment
    />
  );
};

EndTime.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  setFieldErrors: PropTypes.func,
  error: PropTypes.bool.isRequired,
  model: PropTypes.objectOf(PropTypes.any)
};

EndTime.defaultProps = {
  value: null,
  updateFieldValue: f => f,
  setFieldErrors: f => f,
  model: {}
};

const validateEndTime = (val, other) => {
  const { model } = other;
  const end = val.split(':').join();
  const start = model['time_from'].split(':').join();

  return !!(end > start);
};

export const EndTimeField = () => (
  <Field
    name="time_to"
    component={props => <EndTime {...props} />}
    validationFunc={validateEndTime}
    errorMessage="Must be after start time!"
    required
  />
);
