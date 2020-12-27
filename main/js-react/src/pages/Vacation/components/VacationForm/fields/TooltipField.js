import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Input } from '../../../../../ea-components';

const Tooltip = ({ value, updateFieldValue, error }) => (
  <Input
    label={__('Tooltip *', 'easy-appointments')}
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
    multiline
  />
);

Tooltip.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Tooltip.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const TooltipField = () => (
  <Field required name="tooltip" component={props => <Tooltip {...props} />} />
);
