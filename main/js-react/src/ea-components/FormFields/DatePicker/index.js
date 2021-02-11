import React from 'react';

import DateFnsUtils from '@date-io/date-fns';
import EventIcon from '@material-ui/icons/Event';
import { InputAdornment } from '@material-ui/core';
import { MuiPickersUtilsProvider, DatePicker } from '@material-ui/pickers';

const DatePickerField = ({
  value,
  label,
  onChange,
  error,
  minDate,
  adornment
}) => {
  const adornmentProps = adornment
    ? {
        InputProps: {
          endAdornment: (
            <InputAdornment position="end">
              <EventIcon />
            </InputAdornment>
          )
        }
      }
    : {};

  return (
    <MuiPickersUtilsProvider utils={DateFnsUtils}>
      <DatePicker
        className="ea-date-picker"
        variant="inline"
        inputVariant="outlined"
        label={label}
        format="yyyy-MM-dd"
        value={value}
        onChange={onChange}
        error={!!error}
        minDate={minDate}
        fullWidth
        disablePast
        disableToolbar
        {...adornmentProps}
      />
    </MuiPickersUtilsProvider>
  );
};

export default DatePickerField;
