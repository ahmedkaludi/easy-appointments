import React, { useContext, useEffect } from 'react';
import PropTypes from 'prop-types';

import { FormHelperText } from '@material-ui/core';
import { FormContext } from './Form';

const Field = ({ name, required, component, validationFunc, errorMessage }) => {
  const {
    model,
    loading,
    updateValue,
    subscribeValidator,
    unsubscribeValidator,
    setFieldErrors,
    fieldErrors,
    setLoading
  } = useContext(FormContext);

  useEffect(() => {
    subscribeValidator(name, () => requiredValidator(model));

    return () => unsubscribeValidator(name);
  }, [
    model,
    name,
    requiredValidator,
    subscribeValidator,
    unsubscribeValidator
  ]);

  const validationProps = { model, setFieldErrors };

  const customValidation = val => {
    if (typeof validationFunc === 'function') {
      const result = validationFunc(val, validationProps);
      return typeof result === 'boolean' ? result : true;
    }

    return true;
  };

  const requiredValidator = data => {
    if (required && !data[name]) {
      setFieldErrors(name, ['Required field!']);
      return false;
    }

    if (validationFunc && data[name] && !customValidation(data[name])) {
      setFieldErrors(name, [
        errorMessage ?? 'Entered value is not in the right format!'
      ]);
      return false;
    }

    return true;
  };

  const updateFieldValue = val => updateValue(name, val);

  const error = fieldErrors[name] ?? null;

  const props = {
    name,
    model,
    loading,
    updateFieldValue,
    updateValue,
    value: model[name] || null,
    setLoading,
    setFieldErrors,
    error: !!error
  };

  return (
    <div className="ea-form-field">
      {component(props)}
      <FormHelperText error={!!error}>{error}</FormHelperText>
    </div>
  );
};

Field.propTypes = {
  name: PropTypes.string,
  required: PropTypes.bool,
  component: PropTypes.func,
  validationFunc: PropTypes.func,
  errorMessage: PropTypes.string
};

Field.defaultProps = {
  name: '',
  required: false,
  component: f => f,
  validationFunc: null,
  errorMessage: null
};

export default Field;
