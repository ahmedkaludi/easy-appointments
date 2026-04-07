import React from 'react';
import { Field } from '../../../../../ea-components';

export const UnlimitedEndDateField = () => (
  <Field
    name="is_unlimited"
    type="checkbox"
    component={({ value, updateFieldValue }) => (
      <label style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
        <input
          type="checkbox"
          checked={!!value}
          onChange={e => updateFieldValue(e.target.checked ? '1' : '0')}
        />
        Set end date to 50 years (Unlimited)
      </label>
    )}
  />
);
