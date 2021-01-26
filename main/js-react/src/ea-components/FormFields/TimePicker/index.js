import React from 'react';

import DateFnsUtils from '@date-io/date-fns';
import AccessTimeIcon from '@material-ui/icons/AccessTime';
import { InputAdornment } from '@material-ui/core';
import { MuiPickersUtilsProvider, TimePicker } from '@material-ui/pickers';

const TimePickerField = ({ value, label, onChange, error, adornment }) => {
  const adornmentProps = adornment
    ? {
        InputProps: {
          endAdornment: (
            <InputAdornment position="end">
              <AccessTimeIcon />
            </InputAdornment>
          )
        }
      }
    : {};

  return (
    <MuiPickersUtilsProvider utils={DateFnsUtils}>
      <TimePicker
        className="ea-time-picker"
        variant="inline"
        inputVariant="outlined"
        label={label}
        ampm={false}
        openTo="hours"
        views={['hours', 'minutes', 'seconds']}
        format="HH:mm:ss"
        value={value}
        onChange={onChange}
        error={!!error}
        fullWidth
        {...adornmentProps}
        minTime={new Date(0, 0, 0, 8)}
        maxTime={new Date(0, 0, 0, 18, 45)}
      />
    </MuiPickersUtilsProvider>
  );
};

export default TimePickerField;
