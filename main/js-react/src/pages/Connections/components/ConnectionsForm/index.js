import React from 'react';
import PropTypes from 'prop-types';

import { Form, DoubleField, MultiFieldWrap } from '../../../../ea-components';
import { DaysOfWeekField } from './fields/DaysOfWeekField';
import { SelectField } from './fields/SelectField';
import { NumberOfSlotsField } from './fields/NumberOfSlotsField';
import { IsWorkingField } from './fields/IsWorkingField';
import { StartDateField } from './fields/StartDateField';
import { EndDateField } from './fields/EndDateField';
import { StartTimeField } from './fields/StartTimeField';
import { EndTimeField } from './fields/EndTimeField';
import { RepeatWeeksField } from './fields/RepeatWeeksField';

export const ConnectionsForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <SelectField name="location" label="Location *" dataKey="Locations" />
    <SelectField name="service" label="Service *" dataKey="Services" />
    <SelectField name="worker" label="Worker *" dataKey="Workers" />
    <NumberOfSlotsField />
    <DaysOfWeekField />
    <RepeatWeeksField />

    <MultiFieldWrap
      label="Date range"
      className="ea-form-field"
      info="Define date range when this connection is going to be active">
      <DoubleField>
        <StartDateField />
        <EndDateField />
      </DoubleField>
    </MultiFieldWrap>

    <MultiFieldWrap
      label="Time range"
      className="ea-form-field"
      info="Define working hours by selecting start and end time">
      <DoubleField>
        <StartTimeField />
        <EndTimeField />
      </DoubleField>
    </MultiFieldWrap>

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
