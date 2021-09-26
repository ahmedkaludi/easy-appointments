import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';

import { DataService } from '../../../../../services';
import { __ } from '../../../../../services/Localization';
import { Field, Autocomplete } from '../../../../../ea-components';

const MultiSelect = ({ value, label, updateFieldValue, error, dataKey }) => {
  const [options, setOptions] = useState([]);

  const loadOptions = () => {
    let opts = DataService.get(dataKey);

    opts = opts ? opts.map(opt => ({ value: opt.id, label: opt.name })) : [];

    setOptions(opts);
  };

  useEffect(loadOptions, []);

  const onChange = (e, newVal) => {
    updateFieldValue(newVal);
  };

  return (
    <Autocomplete
      label={__(label, 'easy-appointments')}
      placeholder={__(label, 'easy-appointments')}
      value={value || []}
      onChange={onChange}
      options={options}
      error={error}
    />
  );
};

MultiSelect.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired,
  dataKey: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired
};

MultiSelect.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const MultiSelectField = ({ name, dataKey, label }) => (
  <Field
    name={name}
    component={props => (
      <MultiSelect {...props} dataKey={dataKey} label={label} />
    )}
    required
  />
);

MultiSelectField.propTypes = {
  name: PropTypes.string.isRequired,
  dataKey: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired
};
