import React from 'react';
import PropTypes from 'prop-types';

import { Autocomplete as MuiAutocomplete } from '@material-ui/lab';
import { TextField } from '@material-ui/core';

const Autocomplete = ({
  value,
  onChange,
  options,
  label,
  disabled,
  placeholder
}) => (
  <MuiAutocomplete
    multiple
    className="ea-autocomplete"
    options={options}
    getOptionLabel={option => option.label}
    filterSelectedOptions
    size="small"
    limitTags={4}
    defaultValue={[]}
    renderInput={params => (
      <TextField
        {...params}
        fullWidth
        variant="outlined"
        label={label}
        placeholder={placeholder}
      />
    )}
    ChipProps={{
      variant: 'outlined',
      size: 'small',
      color: 'primary'
    }}
  />
);

Autocomplete.propTypes = {
  label: PropTypes.string,
  placeholder: PropTypes.string,
  value: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onChange: PropTypes.func,
  options: PropTypes.arrayOf(PropTypes.any).isRequired,
  disabled: PropTypes.bool
};

Autocomplete.defaultProps = {
  label: '',
  placeholder: '',
  value: null,
  onChange: f => f,
  disabled: false
};

export default Autocomplete;
