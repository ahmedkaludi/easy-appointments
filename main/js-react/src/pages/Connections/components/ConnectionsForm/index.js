import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { DaysOfWeekField } from './fields/DaysOfWeekField';
import { SelectField } from './fields/SelectField';

export const ConnectionsForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <SelectField name="location" label="Location *" dataKey="Locations" />
    <SelectField name="service" label="Service *" dataKey="Services" />
    <SelectField name="worker" label="Worker *" dataKey="Workers" />
    <DaysOfWeekField />
  </Form>
);

ConnectionsForm.propTypes = {
  onSave: PropTypes.func,
  onCancel: PropTypes.func
};

ConnectionsForm.defaultProps = {
  onSave: f => f,
  onCancel: f => f
};
