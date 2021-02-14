import React, { useRef, useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import {
  OutlinedInput,
  InputLabel,
  FormControl,
  InputAdornment
} from '@material-ui/core';

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
  rows,
  adornment,
  min
}) => {
  const [labelWidth, setLabelWidth] = useState(0);
  let inputLabelRef = useRef(null);
  const otherProps = {};

  useEffect(() => {
    const offset = inputLabelRef.current.offsetWidth;
    setLabelWidth(offset);
  }, []);

  if (adornment && adornment?.position && adornment?.text) {
    otherProps[`${adornment.position}Adornment`] = (
      <InputAdornment position={adornment.position}>
        {adornment.text}
      </InputAdornment>
    );
  }

  if (type === 'number') {
    otherProps['inputProps'] = { min: min ?? 0 };
  }

  return (
    <FormControl fullWidth className="ea-input">
      <InputLabel error={!!error} ref={inputLabelRef}>
        {label}
      </InputLabel>
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
        error={!!error}
        autoFocus={autoFocus}
        rowsMax={10}
        {...otherProps}
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
  rows: PropTypes.number,
  adornment: PropTypes.objectOf(PropTypes.any),
  min: PropTypes.number
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
  rows: 1,
  adornment: null,
  min: 0
};

export default Input;
