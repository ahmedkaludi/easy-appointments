import React, { useEffect } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../services/Localization';
import Field from '../../../Form/Field';
import DatePicker from '../../DatePicker';

const formatDate = date => {
  date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
  return date.toJSON().slice(0, 10);
};

const StartDate = ({ value, updateFieldValue, error }) => {
  useEffect(() => {
    if (!value) {
      updateFieldValue(formatDate(new Date()));
    }
  }, [value]);

  const onChange = date => updateFieldValue(formatDate(date));

  return (
    <DatePicker
      label={__('Start date *', 'easy-appointments')}
      value={value}
      onChange={onChange}
      error={error}
      disablePast={false}
      adornment
    />
  );
};

StartDate.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

StartDate.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const StartDateField = ({ name }) => (
  <Field name={name} component={props => <StartDate {...props} />} required />
);
