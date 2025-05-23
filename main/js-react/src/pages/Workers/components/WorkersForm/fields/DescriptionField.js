import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Input } from '../../../../../ea-components';

const Description = ({ value, updateFieldValue, error }) => (
  <Input
    label={__('Description *', 'easy-appointments')}
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
    multiline
  />
);

Description.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Description.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const DescriptionField = () => (
  <Field
    name="description"
    component={props => <Description {...props} />}
    required
  />
);
