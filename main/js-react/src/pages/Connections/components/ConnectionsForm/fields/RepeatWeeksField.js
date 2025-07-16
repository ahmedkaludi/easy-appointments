import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Select } from '../../../../../ea-components';

const WEEK_OPTIONS = [
  { value: '0', label: 'No repeat' },
  { value: '1', label: 'Yes Weekly' }
];

const RepeatWeeks = ({ value, updateFieldValue, error }) => {
  const onChange = e => updateFieldValue(e.target.value);

  return (
    <Select
      label={__('Repeat in every', 'easy-appointments')}
      value={value || ''}
      onChange={onChange}
      options={WEEK_OPTIONS}
      error={error}
    />
  );
};

RepeatWeeks.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

RepeatWeeks.defaultProps = {
  value: '',
  updateFieldValue: f => f
};

export const RepeatWeeksField = () => (
  <Field name="repeat_week" component={props => <RepeatWeeks {...props} />} />
);
