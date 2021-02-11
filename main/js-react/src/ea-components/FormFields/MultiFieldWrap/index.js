import React from 'react';

import InfoOutlinedIcon from '@material-ui/icons/InfoOutlined';

const MultiFieldWrap = ({ label, children, className, info }) => (
  <div className={`ea-multi-wrap ${className ?? ''}`}>
    <div className="ea-multi-wrap__content">
      {info && (
        <div className="d-flex align-items-center mb-3">
          <InfoOutlinedIcon className="ea-info-md text-black-50" />
          <span className="ea-info-sm ml-1">{info}</span>
        </div>
      )}
      {children}
    </div>
    <fieldset className="ea-multi-wrap__fieldset">
      <legend className="ea-multi-wrap__label">
        <span>{label}</span>
      </legend>
    </fieldset>
  </div>
);

export default MultiFieldWrap;
