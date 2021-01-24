import React from 'react';
import PropTypes from 'prop-types';

import { Form, DoubleField } from '../../../../ea-components';
import { DaysOfWeekField } from './fields/DaysOfWeekField';
import { SelectField } from './fields/SelectField';
import { NumberOfSlotsField } from './fields/NumberOfSlotsField';
import { IsWorkingField } from './fields/IsWorkingField';
import { StartDateField } from './fields/StartDateField';
import { EndDateField } from './fields/EndDateField';

export const ConnectionsForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <SelectField name="location" label="Location *" dataKey="Locations" />
    <SelectField name="service" label="Service *" dataKey="Services" />
    <SelectField name="worker" label="Worker *" dataKey="Workers" />
    <NumberOfSlotsField />
    <DaysOfWeekField />
    <DoubleField>
      <StartDateField />
      <EndDateField />
    </DoubleField>
    <IsWorkingField />
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
