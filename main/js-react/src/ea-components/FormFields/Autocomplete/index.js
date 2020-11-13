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
  placeholder,
  limitTags,
  error
}) => {
  const filterOptions = (opts, state) => {
    const selected = value.map(val => val.value);
    let ret = opts.filter(opt => !selected.includes(opt.value));

    if (state?.inputValue && state.inputValue.length) {
      ret = ret.filter(opt =>
        opt.label.toLowerCase().includes(state.inputValue.toLowerCase())
      );
    }

    return ret;
  };

  return (
    <MuiAutocomplete
      multiple
      className="ea-autocomplete"
      options={options}
      getOptionLabel={option => option.label}
      filterSelectedOptions
      disableCloseOnSelect
      disablePortal
      filterOptions={filterOptions}
      size="small"
      limitTags={limitTags}
      value={value}
      onChange={onChange}
      renderInput={params => (
        <TextField
          {...params}
          fullWidth
          variant="outlined"
          label={label}
          placeholder={placeholder}
          error={error}
        />
      )}
      ChipProps={{
        variant: 'outlined',
        size: 'small',
        color: 'primary'
      }}
    />
  );
};

Autocomplete.propTypes = {
  label: PropTypes.string,
  placeholder: PropTypes.string,
  value: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  onChange: PropTypes.func,
  options: PropTypes.arrayOf(PropTypes.any).isRequired,
  error: PropTypes.bool.isRequired,
  disabled: PropTypes.bool,
  limitTags: PropTypes.number
};

Autocomplete.defaultProps = {
  label: '',
  placeholder: '',
  value: null,
  onChange: f => f,
  disabled: false,
  limitTags: -1
};

export default Autocomplete;
