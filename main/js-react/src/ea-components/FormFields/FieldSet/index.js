import React from 'react';
import PropTypes from 'prop-types';

import { TextField } from '@material-ui/core';

const InputComponent = ({ inputRef, ...other }) => (
  <div className="fieldset-content" {...other} />
);

const FieldSet = ({ children, label }) => {
  return (
    <TextField
      variant="outlined"
      className="ea-fieldset"
      label={label}
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
  ])
};

FieldSet.defaultProps = {
  label: '',
  children: null
};

export default FieldSet;
