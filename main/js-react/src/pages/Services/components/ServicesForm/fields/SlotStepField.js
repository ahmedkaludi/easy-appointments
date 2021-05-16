import React, { useEffect, useState } from 'react';
import { __ } from '../../../../../services/Localization';
import { Field, Select } from '../../../../../ea-components';

const calculateOptions = duration => {
  const value = Number.parseInt(duration);
  const options = [];

  if (!value || value === 0) {
    return options;
  }

  for (let i = 1; i <= value; i++) {
    if (value % i !== 0 || i < 5) {
      continue;
    }

    options.push({
      value: `${i}`,
      label: i
    });
  }

  return options.reverse();
};

const SlotStep = ({ value, updateFieldValue, error, model }) => {
  const [options, setOptions] = useState([]);

  useEffect(() => {
    if (!model.duration) {
      return;
    }

    const localOptions = calculateOptions(model.duration);

    setOptions(localOptions);

    if (
      localOptions.length > 0 &&
      !localOptions.some(option => option.value === value)
    ) {
      updateFieldValue(localOptions[0].value);
    }
  }, [model.duration, updateFieldValue, value]);

  const onChange = e => {
    updateFieldValue(e.target.value);
  };

  return (
    <Select
      label={__('Slot step *', 'easy-appointments')}
      value={value || ''}
      onChange={onChange}
      options={options}
      error={error}
      disabled={options.length === 0}
    />
  );
};

export const SlotStepField = () => (
  <Field
    name="slot_step"
    component={props => <SlotStep {...props} />}
    required
  />
);
