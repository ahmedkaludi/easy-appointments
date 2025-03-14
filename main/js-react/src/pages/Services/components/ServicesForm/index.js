import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../services';
import { NameField } from './fields/NameField';
import { PriceField } from './fields/PriceField';
import { MinutesField } from './fields/MinutesField';
import { SlotStepField } from './fields/SlotStepField';
import { DailyLimitField } from './fields/DailyLimitField';
import { ServiceColorField } from './fields/ServiceColorField';
import { Form, MultiFieldWrap } from '../../../../ea-components';

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
    <MultiFieldWrap
      label="Limit of bookings per day"
      className="ea-form-field"
      info="Define hard limit for this service on how many times it can be booked during single day. To disable this check set to 0">
      <DailyLimitField />
    </MultiFieldWrap>
    <PriceField />
    <ServiceColorField />
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
