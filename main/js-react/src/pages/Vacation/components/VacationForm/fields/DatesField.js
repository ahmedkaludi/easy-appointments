import React from 'react';
import PropTypes from 'prop-types';

import { Field, DateMultiselect } from '../../../../../ea-components';

const Dates = ({ value, updateValue, name }) => {
  const onChange = selected => {
    console.log('==========', selected);
  };

  return <DateMultiselect value={value} onChange={onChange} />;
};

Dates.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateValue: PropTypes.func,
  name: PropTypes.string
};

Dates.defaultProps = {
  value: null,
  updateValue: f => f,
  name: ''
};

export const DatesField = () => (
  <Field name="days" component={props => <Dates {...props} />} />
);
