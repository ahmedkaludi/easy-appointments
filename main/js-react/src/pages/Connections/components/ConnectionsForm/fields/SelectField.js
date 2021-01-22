import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import { DataService } from '../../../../../services';
import { __ } from '../../../../../services/Localization';
import { Field, Select } from '../../../../../ea-components';

const EaSelect = ({ value, updateFieldValue, error, dataKey, label }) => {
  const [options, setOptions] = useState([]);

  const loadOptions = () => {
    let opts = DataService.get(dataKey);

    opts = opts ? opts.map(opt => ({ value: opt.id, label: opt.name })) : [];

    setOptions(opts);
  };

  useEffect(loadOptions, []);

  const onChange = e => updateFieldValue(e.target.value);

  return (
    <Select
      label={__(`${label}`, 'easy-appointments')}
      value={value || ''}
      onChange={onChange}
      options={options}
      error={error}
    />
  );
};

EaSelect.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired,
  dataKey: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired
};

EaSelect.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const SelectField = ({ name, dataKey, label }) => (
  <Field
    name={name}
    component={props => <EaSelect {...props} dataKey={dataKey} label={label} />}
    required
  />
);

SelectField.propTypes = {
  name: PropTypes.string.isRequired,
  dataKey: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired
};
