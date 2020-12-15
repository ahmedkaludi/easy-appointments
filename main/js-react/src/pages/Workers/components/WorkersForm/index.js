import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { NameField } from './fields/NameField';
import { DescriptionField } from './fields/DescriptionField';
import { EmailField } from './fields/EmailField';
import { PhoneField } from './fields/PhoneField';

export const WorkersForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <NameField />
    <DescriptionField />
    <EmailField />
    <PhoneField />
  </Form>
);

WorkersForm.propTypes = {
  onSave: PropTypes.func,
  onCancel: PropTypes.func
};

WorkersForm.defaultProps = {
  onSave: f => f,
  onCancel: f => f
};
