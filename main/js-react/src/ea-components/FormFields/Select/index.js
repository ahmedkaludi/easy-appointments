import React from 'react';
import PropTypes from 'prop-types';

import { MenuItem, TextField } from '@material-ui/core';
// import { MenuItem, InputLabel, FormControl } from '@material-ui/core';
// import { Select as MuiSelect } from '@material-ui/core';

const Select = ({ value, onChange, options, label, disabled, customClass }) => {
  const selectClass = `ea-select ${customClass ?? ''}`;

  const renderOptions = () =>
    options.map(option => (
      <MenuItem value={option.value} key={`option-${option.value}`}>
        {option.label}
      </MenuItem>
    ));

  return (
    <TextField
      id="outlined-select"
      className={selectClass}
      fullWidth
      select
      label={label}
      value={value}
      onChange={onChange}
      variant="outlined"
      size="small"
      disabled={disabled}>
      {renderOptions()}
    </TextField>
  );

  // return (
  //   <FormControl variant="outlined" className="ea-select">
  //     <InputLabel>{label}</InputLabel>
  //     <MuiSelect
  //       id="demo-simple-select-outlined"
  //       value={value}
  //       onChange={onChange}
  //       label={label}
  //       disabled={disabled}>
  //       {renderOptions()}
  //     </MuiSelect>
  //   </FormControl>
  // );
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
