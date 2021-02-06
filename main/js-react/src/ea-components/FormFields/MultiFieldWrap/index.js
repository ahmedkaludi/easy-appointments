import React from 'react';

import FieldSet from '../FieldSet';

const MultiFieldWrap = ({ label, children, className }) => (
  <div className="ea-form-field">
    <FieldSet label={label} className={`multiwrap ${className ?? ''}`}>
      {children}
    </FieldSet>
  </div>
);

export default MultiFieldWrap;
