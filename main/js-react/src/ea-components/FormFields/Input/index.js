import React, { useRef, useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import { OutlinedInput, InputLabel, FormControl } from '@material-ui/core';

const Input = ({
  placeholder,
  label,
  value,
  type,
  onChange,
  autoFocus,
  disabled,
  error,
  multiline,
  rows
}) => {
  const [labelWidth, setLabelWidth] = useState(0);
  let inputLabelRef = useRef(null);

  useEffect(() => {
    const offset = inputLabelRef.current.offsetWidth;
    setLabelWidth(offset);
  }, []);

  return (
    <FormControl fullWidth className="ea-input">
      <InputLabel ref={inputLabelRef}>{label}</InputLabel>
      <OutlinedInput
        placeholder={placeholder}
        labelWidth={labelWidth}
        label={label}
        multiline={multiline}
        rows={rows}
        type={type}
        onChange={event => onChange(event.target.value)}
        value={value}
        disabled={disabled}
        error={error}
        autoFocus={autoFocus}
      />
    </FormControl>
  );
};

Input.propTypes = {
  placeholder: PropTypes.string,
  label: PropTypes.string,
  value: PropTypes.any,
  onChange: PropTypes.func,
  autoFocus: PropTypes.bool,
  type: PropTypes.string,
  disabled: PropTypes.bool,
  error: PropTypes.bool,
  multiline: PropTypes.bool,
  rows: PropTypes.number
};

Input.defaultProps = {
  placeholder: '',
  label: '',
  value: '',
  onChange: f => f,
  autoFocus: false,
  type: 'text',
  disabled: false,
  error: false,
  multiline: false,
  rows: 1
};

export default Input;
