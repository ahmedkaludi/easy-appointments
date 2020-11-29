import React from 'react';
import PropTypes from 'prop-types';

import { Field, DateMultiselect } from '../../../../../ea-components';

const Dates = ({ value, updateFieldValue, error }) => {
  const onChange = selected => updateFieldValue(selected);

  return <DateMultiselect error={error} value={value} onChange={onChange} />;
};

Dates.propTypes = {
  value: PropTypes.arrayOf(PropTypes.string),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Dates.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const DatesField = () => (
  <Field required name="days" component={props => <Dates {...props} />} />
);
