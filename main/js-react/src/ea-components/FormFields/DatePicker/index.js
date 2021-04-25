import React from 'react';
import PropTypes from 'prop-types';

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
  adornment,
  disablePast
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
        disablePast={disablePast}
        disableToolbar
        {...adornmentProps}
      />
    </MuiPickersUtilsProvider>
  );
};

DatePickerField.propTypes = {
  value: PropTypes.string,
  label: PropTypes.string,
  onChange: PropTypes.func,
  disablePast: PropTypes.bool
};

DatePickerField.defaultProps = {
  value: null,
  label: '',
  onChange: f => f,
  disablePast: true
};

export default DatePickerField;
