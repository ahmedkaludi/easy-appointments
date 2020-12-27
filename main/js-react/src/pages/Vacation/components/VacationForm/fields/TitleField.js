import React from 'react';
import PropTypes from 'prop-types';

import { _x } from '../../../../../services/Localization';
import { Field, Input } from '../../../../../ea-components';

const Title = ({ value, updateFieldValue, error }) => (
  <Input
    label={_x('Name *', 'vacation', 'easy-appointments')}
    value={value || ''}
    onChange={val => updateFieldValue(val)}
    error={error}
  />
);

Title.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Title.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const TitleField = () => (
  <Field required name="title" component={props => <Title {...props} />} />
);
