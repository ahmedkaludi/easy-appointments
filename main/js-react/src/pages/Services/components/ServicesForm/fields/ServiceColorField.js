import React from 'react';
import PropTypes from 'prop-types';
import { TwitterPicker } from 'react-color';

import { Field } from '../../../../../ea-components';

const COLORS = [
  '#FF6900',
  '#FCB900',
  '#7BDCB5',
  '#00D084',
  '#8ED1FC',
  '#0693E3',
  '#ABB8C3',
  '#EB144C',
  '#F78DA7',
  '#9900EF',
  '#fe4a49',
  '#2ab7ca',
  '#fed766',
  '#e6e6ea',
  '#f4f4f8',
  '#4a4e4d',
  '#095a62',
  '#3da4ab',
  '#f1dca3',
  '#fe8a71'
];

const ServiceColorInput = ({ value, updateFieldValue }) => {
  const onChange = color => {
    updateFieldValue(color.hex);
  };

  return (
    <>
      <div
        style={{
          color: '#fff',
          backgroundColor: value,
          height: '28px',
          borderRadius: '4px',
          marginBottom: '10px',
          textAlign: 'center',
          paddingTop: '4px'
        }}>
        Service color
      </div>
      <TwitterPicker
        triangle="none"
        width="100%"
        colors={COLORS}
        onChange={onChange}
      />
    </>
  );
};

ServiceColorInput.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func
};

ServiceColorInput.defaultProps = {
  value: 'c',
  updateFieldValue: f => f
};

export const ServiceColorField = () => (
  <Field
    name="service_color"
    component={props => <ServiceColorInput {...props} />}
  />
);
