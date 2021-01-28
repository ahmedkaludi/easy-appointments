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

const StartTime = ({
  value,
  updateFieldValue,
  model,
  error,
  setFieldErrors
}) => {
  const handleErrors = () => {
    const start = value.split(':').join();
    const end = model['time_to'].split(':').join();

    setFieldErrors('time_to', end < start ? 'Must be after start time!' : null);
    setFieldErrors(
      'time_from',
      end < start ? 'Must be before end time!' : null
    );
  };

  useEffect(() => {
    if (!value) {
      updateFieldValue('00:00:00');
      return;
    }

    handleErrors();
  }, [value]);

  const onChange = date => updateFieldValue(dateToTime(date));

  return (
    <TimePicker
      label={__('Start time *', 'easy-appointments')}
      value={timeToDate(value)}
      onChange={onChange}
      error={error}
      adornment
    />
  );
};

StartTime.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  setFieldErrors: PropTypes.func,
  error: PropTypes.bool.isRequired,
  model: PropTypes.objectOf(PropTypes.any)
};

StartTime.defaultProps = {
  value: null,
  updateFieldValue: f => f,
  setFieldErrors: f => f,
  model: {}
};

const validateStartTime = (val, other) => {
  const { model } = other;
  const start = val.split(':').join();
  const end = model['time_to'].split(':').join();

  return !!(start < end);
};

export const StartTimeField = () => (
  <Field
    name="time_from"
    component={props => <StartTime {...props} />}
    validationFunc={validateStartTime}
    errorMessage="Must be before end time!"
    required
  />
);
