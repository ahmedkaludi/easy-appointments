import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, TimePicker } from '../../../../../ea-components';

const EndTime = ({ value, updateFieldValue, error }) => {
  useEffect(() => {
    if (!value) {
      updateFieldValue(new Date());
    }
  }, [value]);

  const onChange = date => updateFieldValue(date);

  return (
    <TimePicker
      label={__('End time *', 'easy-appointments')}
      value={value}
      onChange={onChange}
      error={error}
      adornment
    />
  );
};

EndTime.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

EndTime.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const EndTimeField = () => (
  <Field name="time_to" component={props => <EndTime {...props} />} required />
);
