import React from 'react';
import PropTypes from 'prop-types';

import { NameField } from './fields/NameField';
import { Form } from '../../../../ea-components';
import { PriceField } from './fields/PriceField';
import { MinutesField } from './fields/MinutesField';
import { __ } from '../../../../services/Localization';
import { SlotStepField } from './fields/SlotStepField';

export const ServicesForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <NameField />
    <MinutesField
      name="duration"
      label={__('Duration *', 'easy-appointments')}
    />
    <SlotStepField />
    <MinutesField
      name="block_before"
      label={__('Block before *', 'easy-appointments')}
    />
    <MinutesField
      name="block_after"
      label={__('Block after *', 'easy-appointments')}
    />
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
