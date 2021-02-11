import React from 'react';
import PropTypes from 'prop-types';

import { Field, Input } from '../../../../../ea-components';
import { __ } from '../../../../../services/Localization';

const NumberOfSlots = ({ value, updateFieldValue, error, label }) => {
  return (
    <Input
      label={__('Number of slots *', 'easy-appointments')}
      value={value || '0'}
      onChange={val => updateFieldValue(val)}
      error={error}
      type="number"
    />
  );
};

NumberOfSlots.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

NumberOfSlots.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const NumberOfSlotsField = () => (
  <Field
    name="slot_count"
    component={props => <NumberOfSlots {...props} />}
    required
  />
);
