import React, { useState, useEffect, useRef } from 'react';
import PropTypes from 'prop-types';

// import { MenuItem, TextField } from '@material-ui/core';
import {
  MenuItem,
  InputLabel,
  FormControl,
  OutlinedInput,
  Chip
} from '@material-ui/core';
import { Select as MuiSelect } from '@material-ui/core';

const MultiSelect = ({ value, onChange, options, label, disabled }) => {
  const [labelWidth, setLabelWidth] = useState(0);
  let inputLabelRef = useRef(null);

  useEffect(() => {
    const offset = inputLabelRef.current.offsetWidth;
    setLabelWidth(offset);
  }, []);

  const renderOptions = () =>
    options.map(option => (
      <MenuItem value={option.value} key={`option-${option.value}`}>
        {option.label}
      </MenuItem>
    ));

  return (
    <FormControl variant="outlined" fullWidth className="ea-select">
      <InputLabel ref={inputLabelRef} htmlFor="select-multiple-chip">
        {label}
      </InputLabel>
      <MuiSelect
        multiple
        value={value}
        onChange={onChange}
        MenuProps={{
          anchorOrigin: {
            vertical: 'bottom',
            horizontal: 'left'
          },
          // transformOrigin: {
          //   vertical: 'top',
          //   horizontal: 'left'
          // },
          getContentAnchorEl: null
        }}
        input={
          <OutlinedInput
            size="small"
            labelWidth={labelWidth}
            id="select-multiple-chip"
            rows={1}
          />
        }
        renderValue={selected => (
          <div className="cls">
            {selected.map(value => (
              <Chip
                key={value}
                label={label}
                className="chip"
                variant="outlined"
                size="small"
                onDelete={f => f}
                color="primary"
              />
            ))}
          </div>
        )}
        disabled={disabled}>
        {renderOptions()}
      </MuiSelect>
    </FormControl>
  );
};

MultiSelect.propTypes = {
  label: PropTypes.string,
  value: PropTypes.any,
  onChange: PropTypes.func,
  options: PropTypes.arrayOf(PropTypes.any).isRequired,
  disabled: PropTypes.bool
};

MultiSelect.defaultProps = {
  label: '',
  value: '',
  onChange: f => f,
  disabled: false
};

export default MultiSelect;
