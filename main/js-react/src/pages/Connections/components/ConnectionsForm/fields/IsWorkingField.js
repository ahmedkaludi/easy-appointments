import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Select } from '../../../../../ea-components';

const OPTIONS = [
  { value: '0', label: 'No' },
  { value: '1', label: 'Yes' }
];

const IsWorking = ({ value, updateFieldValue, error }) => {
  const onChange = e => updateFieldValue(e.target.value);

  return (
    <Select
      label={__('Is working', 'easy-appointments')}
      value={value || ''}
      onChange={onChange}
      options={OPTIONS}
      error={error}
    />
  );
};

IsWorking.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

IsWorking.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const IsWorkingField = () => (
  <Field
    name="is_working"
    component={props => <IsWorking {...props} />}
    required
  />
);
