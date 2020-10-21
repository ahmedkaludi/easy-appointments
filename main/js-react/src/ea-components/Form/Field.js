import React, { useContext, useEffect } from 'react';
import PropTypes from 'prop-types';

import { FormContext } from './Form';

const Field = ({ name, required, component, validationFunc }) => {
  const {
    model,
    updateValue,
    subscribeValidator,
    unsubscribeValidator,
    validate,
    setFieldErrors,
    fieldErrors,
    setLoading
  } = useContext(FormContext);

  const errors = fieldErrors[name] ?? [];

  useEffect(() => {
    if (!required) {
      return;
    }
    subscribeValidator(name, () => requiredValidator(model));

    return () => unsubscribeValidator(name);
  }, [model]);

  const customValidation = val => {
    if (typeof validationFunc === 'function') {
      const result = validationFunc(val);
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
      setFieldErrors(name, ['Entered value is not in the right format!']);
      return false;
    }

    return true;
  };

  const props = {
    name,
    model,
    updateValue,
    value: model[name] || null,
    setLoading,
    validate
  };

  return (
    <div className={`ea-form-field ${errors.length ? 'error' : ''}`}>
      {component(props)}
      {errors.map(error => (
        <div key={error} className="field-error">
          {error}
        </div>
      ))}
    </div>
  );
};

Field.propTypes = {
  name: PropTypes.string,
  required: PropTypes.bool,
  component: PropTypes.func,
  validationFunc: PropTypes.func
};

Field.defaultProps = {
  name: '',
  required: false,
  component: f => f,
  validationFunc: null
};

export default Field;
