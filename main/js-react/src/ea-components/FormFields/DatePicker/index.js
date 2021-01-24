import React from 'react';

import DateFnsUtils from '@date-io/date-fns';
import { MuiPickersUtilsProvider, DatePicker } from '@material-ui/pickers';

const DatePickerField = ({ value, label, onChange, error, minDate }) => (
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
    />
  </MuiPickersUtilsProvider>
);

export default DatePickerField;
