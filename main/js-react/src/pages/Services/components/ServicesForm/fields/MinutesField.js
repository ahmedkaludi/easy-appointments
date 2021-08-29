import React from 'react';
import PropTypes from 'prop-types';

import { Field, Input } from '../../../../../ea-components';
import { __ } from '../../../../../services';

const Minutes = ({ value, updateFieldValue, error, label }) => {
  if (!value) updateFieldValue('0');

  return (
    <Input
      label={label}
      value={value}
      onChange={val => updateFieldValue(val)}
      error={error}
      type="number"
      adornment={{ position: 'end', text: __('minutes', 'easy-appointments') }}
    />
  );
};

Minutes.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Minutes.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const MinutesField = ({ name, label }) => (
  <Field
    name={name}
    component={props => <Minutes {...props} label={label} />}
    required
  />
);
