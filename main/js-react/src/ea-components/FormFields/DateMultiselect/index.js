import React from 'react';

import DateFnsUtils from '@date-io/date-fns';
import { MuiPickersUtilsProvider, DatePicker, Day } from '@material-ui/pickers';
import FieldSet from '../FieldSet';

const style = {
  content: {
    display: 'flex',
    flexWrap: 'wrap'
  },
  dates: {
    backgroundColor: '#f9f9f9',
    flex: '1'
  }
};

const DateMultiselect = ({ value, onChange }) => {
  // const [selectedDate, handleDateChange] = useState(new Date());
  const renderDayComponent = (
    day,
    selectedDate,
    isInCurrentMonth,
    dayComponent
  ) => <Day>{dayComponent}</Day>;

  return (
    <MuiPickersUtilsProvider utils={DateFnsUtils}>
      <FieldSet label="Dates">
        <div style={style.content}>
          <DatePicker
            className="ea-date-multiselect"
            disableToolbar
            variant="static"
            value={value}
            onChange={onChange}
            renderDay={renderDayComponent}
          />
          <div style={style.dates} />
        </div>
      </FieldSet>
    </MuiPickersUtilsProvider>
  );
};

export default DateMultiselect;
