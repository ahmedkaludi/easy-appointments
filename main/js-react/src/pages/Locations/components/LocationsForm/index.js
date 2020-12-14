import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { NameField } from './fields/NameField';
import { AddressField } from './fields/AddressField';
import { LocationField } from './fields/LocationField';

export const LocationsForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <NameField />
    <AddressField />
    <LocationField />
  </Form>
);

LocationsForm.propTypes = {
  onSave: PropTypes.func,
  onCancel: PropTypes.func
};

LocationsForm.defaultProps = {
  onSave: f => f,
  onCancel: f => f
};
