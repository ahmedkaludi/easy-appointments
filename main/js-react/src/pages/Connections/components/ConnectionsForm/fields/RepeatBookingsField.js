import React from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Select } from '../../../../../ea-components';

const WEEK_OPTIONS = [
  { value: '0', label: __('No', 'easy-appointments') },
  { value: '1', label: __('Yes', 'easy-appointments') }
];

const RepeatBookings = ({ value, updateFieldValue, error }) => {
  const handleSelectChange = e => {
    updateFieldValue(e.target.value);
  };

  return (
    <div>
      <Select
        label={__('Allow Repeat Booking', 'easy-appointments')}
        value={value || '0'}
        onChange={handleSelectChange}
        options={WEEK_OPTIONS}
        error={error}
      />
    </div>
  );
};

RepeatBookings.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

RepeatBookings.defaultProps = {
  value: '0',
  updateFieldValue: f => f
};

export const RepeatBookingsField = () => (
  <Field
    name="repeat_booking"
    component={props => <RepeatBookings {...props} />}
  />
);
