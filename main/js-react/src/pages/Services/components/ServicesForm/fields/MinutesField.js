import React from 'react';
import PropTypes from 'prop-types';

import { Field, Input } from '../../../../../ea-components';

const Minutes = ({ value, updateFieldValue, error, label }) => (
  <Input
    label={label}
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
    type="number"
  />
);

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
