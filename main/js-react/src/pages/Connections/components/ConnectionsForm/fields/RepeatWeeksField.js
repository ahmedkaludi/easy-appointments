import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../../../../services/Localization';
import { Field, Select, Input } from '../../../../../ea-components';

const WEEK_OPTIONS = [
  { value: '0', label: __('Weekly', 'easy-appointments') },
  { value: '2', label: __('Every Second Week', 'easy-appointments') },
  { value: 'custom', label: __('Custom Week', 'easy-appointments') }
];

const RepeatWeeks = ({ value, updateFieldValue, error }) => {
  const isCustom = value && !['0', '2'].includes(value);
  const [customVal, setCustomVal] = useState(isCustom ? value : '');

  useEffect(() => {
    if (isCustom && parseInt(value) >= 3) {
      setCustomVal(value);
    }
  }, [value]);

  const handleSelectChange = e => {
    const selected = e.target.value;
    if (selected === 'custom') {
      updateFieldValue(customVal || '3'); // fallback to 3 if empty
    } else {
      updateFieldValue(selected);
    }
  };

  const handleCustomInput = e => {
    const val = e?.target?.value ?? e?.value ?? e;
    if (/^\d*$/.test(val)) {
      setCustomVal(val);
      if (parseInt(val) >= 3) {
        updateFieldValue(val);
      }
    }
  };

  return (
    <div>
      <Select
        label={__('Repeat', 'easy-appointments')}
        value={isCustom ? 'custom' : value || '0'}
        onChange={handleSelectChange}
        options={WEEK_OPTIONS}
        error={error}
      />

      {isCustom && (
        <div style={{ marginTop: '10px' }}>
          <Input
            label={__('Custom Week Number (≥ 3)', 'easy-appointments')}
            type="text"
            inputMode="numeric"
            pattern="[0-9]*"
            value={customVal}
            onChange={handleCustomInput}
            placeholder={__('Enter custom week number', 'easy-appointments')}
            error={error}
          />
        </div>
      )}
    </div>
  );
};

RepeatWeeks.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

RepeatWeeks.defaultProps = {
  value: '0',
  updateFieldValue: f => f
};

export const RepeatWeeksField = () => (
  <Field name="repeat_week" component={props => <RepeatWeeks {...props} />} />
);
