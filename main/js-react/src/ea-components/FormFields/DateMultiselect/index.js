import React, { useState } from 'react';

import DateFnsUtils from '@date-io/date-fns';
import { MuiPickersUtilsProvider, DatePicker } from '@material-ui/pickers';
import FieldSet from '../FieldSet';

const DateMultiselect = props => {
  const [selectedDate, handleDateChange] = useState(new Date());

  const style = {
    content: {
      display: 'flex',
      flexWrap: 'wrap'
    },
    dates: {
      backgroundColor: 'red',
      flex: '1'
    }
  };

  return (
    <MuiPickersUtilsProvider utils={DateFnsUtils}>
      <FieldSet label="Dates">
        <div style={style.content}>
          <DatePicker
            className="ea-date-multiselect"
            disableToolbar
            variant="static"
            value={selectedDate}
            onChange={handleDateChange}
          />
          <div style={style.dates} />
        </div>
      </FieldSet>
    </MuiPickersUtilsProvider>
  );
};

export default DateMultiselect;
