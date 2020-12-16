import React from 'react';
import PropTypes from 'prop-types';

import { Field, Input } from '../../../../../ea-components';

const Name = ({ value, updateFieldValue, error }) => (
  <Input
    label="Name *"
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
    multiline
  />
);

Name.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Name.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const NameField = () => (
  <Field required name="name" component={props => <Name {...props} />} />
);
