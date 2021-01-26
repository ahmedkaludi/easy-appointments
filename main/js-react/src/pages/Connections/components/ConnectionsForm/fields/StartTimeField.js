import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, TimePicker } from '../../../../../ea-components';

const StartTime = ({ value, updateFieldValue, error }) => {
  useEffect(() => {
    if (!value) {
      const initial = new Date();
      initial.setHours(0, 0, 0);

      updateFieldValue(initial);
    }
  }, [value]);

  const onChange = date => {
    console.log('=======>', date);
    updateFieldValue(date);
  };

  return (
    <TimePicker
      label={__('Start time *', 'easy-appointments')}
      value={value}
      onChange={onChange}
      error={error}
      adornment
    />
  );
};

StartTime.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

StartTime.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const StartTimeField = () => (
  <Field
    name="time_from"
    component={props => <StartTime {...props} />}
    required
  />
);
