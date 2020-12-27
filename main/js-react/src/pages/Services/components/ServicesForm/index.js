import React from 'react';
import PropTypes from 'prop-types';

import { NameField } from './fields/NameField';
import { Form } from '../../../../ea-components';
import { PriceField } from './fields/PriceField';
import { MinutesField } from './fields/MinutesField';
import { __ } from '../../../../services/Localization';

export const ServicesForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <NameField />
    <MinutesField
      name="duration"
      label={__('Duration *', 'easy-appointments')}
    />
    <MinutesField
      name="slot_step"
      label={__('Slot step *', 'easy-appointments')}
    />
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
