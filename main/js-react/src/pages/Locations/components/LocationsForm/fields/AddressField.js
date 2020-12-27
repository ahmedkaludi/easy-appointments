import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Input } from '../../../../../ea-components';

const Address = ({ value, updateFieldValue, error }) => (
  <Input
    label={__('Address *', 'easy-appointments')}
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
    multiline
  />
);

Address.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Address.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const AddressField = () => (
  <Field required name="address" component={props => <Address {...props} />} />
);
