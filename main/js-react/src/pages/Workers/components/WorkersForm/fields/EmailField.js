import React from 'react';
import PropTypes from 'prop-types';

import { Field, Input } from '../../../../../ea-components';

const Email = ({ value, updateFieldValue, error }) => (
  <Input
    label="Email *"
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
  />
);

Email.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Email.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

const emailValidator = user => {
  const format = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; // eslint-disable-line
  return format.test(user);
};

export const EmailField = () => (
  <Field
    name="email"
    component={props => <Email {...props} />}
    validationFunc={emailValidator}
    required
  />
);
