import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Input } from '../../../../../ea-components';

const Location = ({ value, updateFieldValue, error }) => (
  <Input
    label={__('Location *', 'easy-appointments')}
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
    multiline
  />
);

Location.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Location.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const LocationField = () => (
  <Field
    name="location"
    component={props => <Location {...props} />}
    required
  />
);
