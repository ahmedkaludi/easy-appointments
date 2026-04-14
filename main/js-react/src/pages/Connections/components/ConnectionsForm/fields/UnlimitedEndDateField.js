import React, { useEffect } from 'react';
import { Field } from '../../../../../ea-components';

const isUnlimitedRange = (from, to) => {
  if (!from || !to) return false;

  const start = new Date(from);
  const end = new Date(to);

  const diffYears = end.getFullYear() - start.getFullYear();

  return diffYears >= 10; // detect ~50 years
};

// ✅ Inner component (hooks allowed here)
const UnlimitedCheckbox = ({ value, updateFieldValue, fromDate, toDate }) => {
  useEffect(() => {
    if (isUnlimitedRange(fromDate, toDate)) {
      if (value !== '1') {
        updateFieldValue('1');
      }
    } else {
      if (value !== '0') {
        updateFieldValue('0');
      }
    }
  }, [fromDate, toDate]);

  return (
    <label
      style={{
        display: 'flex',
        alignItems: 'center',
        gap: '8px',
        marginTop: '-20px'
      }}>
      <input
        type="checkbox"
        checked={value === '1'}
        onChange={e => {
          const checked = e.target.checked;
          // update checkbox
          updateFieldValue(checked ? '1' : '0');

          if (!fromDate) return;

          let formatted;

          if (checked) {
            const futureDate = new Date(fromDate);
            futureDate.setFullYear(futureDate.getFullYear() + 50);
            formatted = futureDate.toISOString().split('T')[0];
          } else {
            const nextDay = new Date(fromDate);
            nextDay.setDate(nextDay.getDate() + 1);
            formatted = nextDay.toISOString().split('T')[0];
          }

          // ✅ update day_to via global event (EA system)
          const event = new CustomEvent('ea:updateField', {
            detail: { name: 'day_to', value: formatted }
          });
          window.dispatchEvent(event);
        }}
      />
      Infinite End Date
    </label>
  );
};

// ✅ Main export (single file)
export const UnlimitedEndDateField = () => (
  <Field
    name="day_from"
    component={({ value: fromDate }) => (
      <Field
        name="day_to"
        component={({ value: toDate }) => (
          <Field
            name="is_unlimited"
            component={({ value, updateFieldValue }) => (
              <UnlimitedCheckbox
                value={value}
                updateFieldValue={updateFieldValue}
                fromDate={fromDate}
                toDate={toDate}
              />
            )}
          />
        )}
      />
    )}
  />
);
