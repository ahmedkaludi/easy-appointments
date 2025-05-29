import React from 'react';
import PropTypes from 'prop-types';
import { TextField } from '@material-ui/core';
import NumberFormat from 'react-number-format';
import { Field } from '../../../../../ea-components';
import { __ } from '../../../../../services';

const AdvanceBookingDayInput = ({ value, updateFieldValue, error }) => {
  const textFieldProps = {
    className: 'ea-input',
    fullWidth: true,
    label: __('Advance Booking', 'easy-appointments'),
    variant: 'outlined',
    error: !!error
  };

  const handleValueChange = ({ floatValue }) => {
    updateFieldValue(floatValue ?? 0);
  };

  return (
    <NumberFormat
      value={value ?? 0}
      onValueChange={handleValueChange}
      decimalScale={0}
      defaultValue={0}
      allowNegative={false}
      customInput={TextField}
      {...textFieldProps}
    />
  );
};

AdvanceBookingDayInput.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

AdvanceBookingDayInput.defaultProps = {
  value: 0,
  updateFieldValue: f => f
};

export const AdvanceBookingDayField = () => (
  <Field
    name="advance_booking_days"
    component={props => <AdvanceBookingDayInput {...props} />}
  />
);
