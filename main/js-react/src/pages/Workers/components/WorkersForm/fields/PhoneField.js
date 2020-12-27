import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Input } from '../../../../../ea-components';

const Phone = ({ value, updateFieldValue, error }) => (
  <Input
    label={__('Phone *', 'easy-appointments')}
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
  />
);

Phone.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Phone.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const PhoneField = () => (
  <Field name="phone" component={props => <Phone {...props} />} required />
);
