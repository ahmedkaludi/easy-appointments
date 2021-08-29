import React from 'react';
import PropTypes from 'prop-types';

import { TextField } from '@material-ui/core';
import NumberFormat from 'react-number-format';
import { Field } from '../../../../../ea-components';
import { __ } from '../../../../../services';

const DailyLimitInput = ({ value, updateFieldValue, error }) => {
  const textFieldProps = {
    className: 'ea-input',
    fullWidth: true,
    label: __('Daily limit', 'easy-appointments'),
    variant: 'outlined',
    error: !!error
  };

  const onChange = ({ formattedValue }) => updateFieldValue(formattedValue);

  return (
    <NumberFormat
      value={value}
      onValueChange={onChange}
      decimalScale={0}
      defaultValue={0}
      allowNegative={false}
      customInput={TextField}
      {...textFieldProps}
    />
  );
};

DailyLimitInput.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

DailyLimitInput.defaultProps = {
  value: 0,
  updateFieldValue: f => f
};

export const DailyLimitField = () => (
  <Field
    name="daily_limit"
    component={props => <DailyLimitInput {...props} />}
  />
);
