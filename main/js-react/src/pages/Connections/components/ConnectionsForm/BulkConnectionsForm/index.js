import React from 'react';
import PropTypes from 'prop-types';

import {
  Form,
  DoubleField,
  MultiFieldWrap
} from '../../../../../ea-components';
import { DaysOfWeekField } from '../fields/DaysOfWeekField';

import { NumberOfSlotsField } from '../fields/NumberOfSlotsField';
import { IsWorkingField } from '../fields/IsWorkingField';
import { StartDateField } from '../fields/StartDateField';
import { EndDateField } from '../fields/EndDateField';
import { StartTimeField } from '../fields/StartTimeField';
import { EndTimeField } from '../fields/EndTimeField';
import { MultiSelectField } from '../fields/MultiSelectField';
import { RepeatWeeksField } from '../fields/RepeatWeeksField';
import { RepeatBookingsField } from '../fields/RepeatBookingsField';

export const BulkConnectionsForm = ({ model, onBulkSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onBulkSave}>
    <MultiSelectField name="location" label="Locations *" dataKey="Locations" />
    <MultiSelectField name="service" label="Services *" dataKey="Services" />
    <MultiSelectField name="worker" label="Workers *" dataKey="Workers" />
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
    <RepeatBookingsField />
  </Form>
);

BulkConnectionsForm.propTypes = {
  onBulkSave: PropTypes.func,
  onCancel: PropTypes.func
};

BulkConnectionsForm.defaultProps = {
  onBulkSave: f => f,
  onCancel: f => f
};
