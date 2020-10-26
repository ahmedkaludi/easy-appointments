import React from 'react';
import PropTypes from 'prop-types';

import { Field, Input } from '../../../../../ea-components';

const Tooltip = ({ value, updateValue, name }) => (
  <Input
    label="Tooltip"
    value={value || ''}
    onChange={val => updateValue(name, val)}
  />
);

Tooltip.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateValue: PropTypes.func,
  name: PropTypes.string
};

Tooltip.defaultProps = {
  value: null,
  updateValue: f => f,
  name: ''
};

export const TooltipField = () => (
  <Field name="tooltip" component={props => <Tooltip {...props} />} />
);
