// file: fields/TimeRangeField.js
import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import { Field, TimePicker } from '../../../../../ea-components';

const TimeRange = ({ value, updateFieldValue, error }) => {
  // Normalize incoming value to handle multiple formats
  const normalizeValue = val => {
    if (!val) return { startTime: null, endTime: null, fullDay: true };

    if (val.time) return val.time; // handle nested time object
    return {
      startTime: val.startTime ?? null,
      endTime: val.endTime ?? null,
      fullDay:
        typeof val.fullDay !== 'undefined'
          ? val.fullDay
          : !val.startTime && !val.endTime
    };
  };

  const initial = normalizeValue(value);
  const [fullDay, setFullDay] = useState(initial.fullDay);
  const [startTime, setStartTime] = useState(initial.startTime);
  const [endTime, setEndTime] = useState(initial.endTime);

  // ✅ Effect: runs when editing an existing vacation
  useEffect(() => {
    if (!value) return;

    const v = normalizeValue(value);

    // Only update if actual data changed (prevents infinite loop)
    const hasChanged =
      v.fullDay !== fullDay ||
      v.startTime !== startTime ||
      v.endTime !== endTime;

    if (!hasChanged) return;

    setFullDay(v.fullDay);
    setStartTime(v.startTime);
    setEndTime(v.endTime);

    // Update form model once per change
    updateFieldValue({
      startTime: v.fullDay ? null : v.startTime,
      endTime: v.fullDay ? null : v.endTime,
      fullDay: v.fullDay
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [JSON.stringify(value)]); // deep compare without loops

  const handleFullDayChange = checked => {
    setFullDay(checked);
    updateFieldValue({
      startTime: checked ? null : startTime,
      endTime: checked ? null : endTime,
      fullDay: checked
    });
  };

  const handleTimeChange = (type, val) => {
    if (type === 'start') {
      setStartTime(val);
      updateFieldValue({
        startTime: val,
        endTime,
        fullDay
      });
    } else {
      setEndTime(val);
      updateFieldValue({
        startTime,
        endTime: val,
        fullDay
      });
    }
  };

  return (
    <div
      style={{
        display: 'flex',
        flexDirection: 'column',
        gap: '10px',
        marginTop: 8
      }}>
      <div style={{ display: 'flex', alignItems: 'center' }}>
        <input
          type="checkbox"
          checked={fullDay}
          onChange={e => handleFullDayChange(e.target.checked)}
          style={{ marginRight: 8 }}
        />
        <label>Full Day Vacation</label>
      </div>

      {!fullDay && (
        <>
          <label>
            <strong>Start Time</strong>
          </label>
          <TimePicker
            error={error}
            value={startTime}
            onChange={val => handleTimeChange('start', val)}
          />

          <label>
            <strong>End Time</strong>
          </label>
          <TimePicker
            error={error}
            value={endTime}
            onChange={val => handleTimeChange('end', val)}
          />
        </>
      )}
    </div>
  );
};

TimeRange.propTypes = {
  value: PropTypes.oneOfType([
    PropTypes.shape({
      startTime: PropTypes.string,
      endTime: PropTypes.string,
      fullDay: PropTypes.bool
    }),
    PropTypes.object
  ]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

TimeRange.defaultProps = {
  value: { startTime: null, endTime: null, fullDay: true },
  updateFieldValue: f => f
};

export const TimeRangeField = () => (
  <Field name="time" component={props => <TimeRange {...props} />} />
);
