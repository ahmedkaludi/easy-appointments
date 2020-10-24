import React from 'react';
import PropTypes from 'prop-types';

import { Field, Input } from '../../../../../ea-components';

const Title = ({ value, updateValue, name }) => (
  <Input
    label="Title"
    value={value || ''}
    onChange={val => updateValue(name, val)}
  />
);

Title.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateValue: PropTypes.func,
  name: PropTypes.string
};

Title.defaultProps = {
  value: null,
  updateValue: f => f,
  name: ''
};

export const TitleField = () => (
  <Field name="title" component={props => <Title {...props} />} />
);
