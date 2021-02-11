import React from 'react';
import PropTypes from 'prop-types';

import { TextField } from '@material-ui/core';

const InputComponent = ({ inputRef, ...other }) => (
  <div className="fieldset-content" {...other} />
);

const FieldSet = ({ children, label, error, className }) => {
  return (
    <TextField
      variant="outlined"
      className={`ea-fieldset ${className ?? ''}`}
      label={label}
      error={error}
      multiline
      InputLabelProps={{ shrink: true }}
      InputProps={{
        inputComponent: InputComponent
      }}
      inputProps={{ children: children }}
    />
  );
};

FieldSet.propTypes = {
  label: PropTypes.string,
  children: PropTypes.oneOfType([
    PropTypes.node,
    PropTypes.element,
    PropTypes.arrayOf(PropTypes.element)
  ]),
  error: PropTypes.bool.isRequired
};

FieldSet.defaultProps = {
  label: '',
  children: null
};

export default FieldSet;
