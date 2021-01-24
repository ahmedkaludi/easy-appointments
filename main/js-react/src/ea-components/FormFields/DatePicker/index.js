import React from 'react';

import DateFnsUtils from '@date-io/date-fns';
import { MuiPickersUtilsProvider, DatePicker } from '@material-ui/pickers';

// const formatDate = date => {
//   date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
//   return date.toJSON().slice(0, 10);
// };

const DatePickerField = ({ value, label, onChange, error }) => {
  // const [selectedDate, handleDateChange] = useState(new Date());

  // const handleChange = selected => {
  //   console.log('========', formatDate(selected));

  //   // onChange(selected);
  //   handleDateChange(formatDate(selected));
  // };

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
        fullWidth
        disablePast
        disableToolbar
      />
    </MuiPickersUtilsProvider>
  );
};

export default DatePickerField;
