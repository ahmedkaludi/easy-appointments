import React from 'react';
import PropTypes from 'prop-types';

import { MenuItem, TextField } from '@material-ui/core';

const Select = ({ value, onChange, options, label, disabled }) => {
  const renderOptions = () =>
    options.map(option => (
      <MenuItem value={option.value} key={`option-${option.value}`}>
        {option.label}
      </MenuItem>
    ));

  return (
    <div className="ea-select">
      <TextField
        select
        label={label}
        value={value}
        onChange={onChange}
        variant="outlined"
        disabled={disabled}>
        {renderOptions()}
      </TextField>
    </div>
  );
};

Select.propTypes = {
  label: PropTypes.string,
  value: PropTypes.any,
  onChange: PropTypes.func,
  options: PropTypes.arrayOf(PropTypes.any).isRequired,
  disabled: PropTypes.bool
};

Select.defaultProps = {
  label: '',
  value: '',
  onChange: f => f,
  disabled: false
};

export default Select;
