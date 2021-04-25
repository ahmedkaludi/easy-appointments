import React, { useContext } from 'react';

import { FormContext } from './Form';

export const applyFormProps = Component => props => {
  const { model, loading, setLoading, updateValue } = useContext(FormContext);

  const contextProps = { model, loading, setLoading, updateValue };

  return (
    <>
      <Component {...props} {...contextProps} />
    </>
  );
};
