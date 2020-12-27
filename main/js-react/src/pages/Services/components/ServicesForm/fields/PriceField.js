import React from 'react';
import PropTypes from 'prop-types';

import { TextField } from '@material-ui/core';
import NumberFormat from 'react-number-format';
import { Field } from '../../../../../ea-components';
import { __ } from '../../../../../services/Localization';

const Price = ({ value, updateFieldValue, error }) => {
  const textFieldProps = {
    className: 'ea-input',
    fullWidth: true,
    label: __('Price *', 'easy-appointments'),
    variant: 'outlined',
    error: !!error
  };

  const onChange = ({ formattedValue }) => updateFieldValue(formattedValue);

  return (
    <NumberFormat
      value={value}
      onValueChange={onChange}
      decimalScale={2}
      fixedDecimalScale
      thousandSeparator
      customInput={TextField}
      {...textFieldProps}
    />
  );
};

Price.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Price.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const PriceField = () => (
  <Field required name="price" component={props => <Price {...props} />} />
);
