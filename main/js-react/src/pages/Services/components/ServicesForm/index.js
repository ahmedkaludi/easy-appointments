import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { NameField } from './fields/NameField';
import { MinutesField } from './fields/MinutesField';
import { PriceField } from './fields/PriceField';

export const ServicesForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <NameField />
    <MinutesField name="duration" label="Duration *" />
    <MinutesField name="slot_step" label="Slot step *" />
    <MinutesField name="block_before" label="Block before *" />
    <MinutesField name="block_after" label="Block after *" />
    <PriceField />
  </Form>
);

ServicesForm.propTypes = {
  onSave: PropTypes.func,
  onCancel: PropTypes.func
};

ServicesForm.defaultProps = {
  onSave: f => f,
  onCancel: f => f
};
