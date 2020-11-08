import React, { useState } from 'react';

import DateFnsUtils from '@date-io/date-fns';
import { MuiPickersUtilsProvider, DatePicker } from '@material-ui/pickers';
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

const newValueArray = (selected, arr) => {
  const freshArr = arr.includes(selected)
    ? arr.filter(item => item !== selected)
    : [...arr, selected];

  return freshArr.length ? freshArr : null;
};

const formatDate = date => {
  date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
  return date.toJSON().slice(0, 10);
};

const sortSelected = selected => {
  return selected.sort(function(a, b) {
    a = a
      .split('-')
      .reverse()
      .join('');

    b = b
      .split('-')
      .reverse()
      .join('');

    return a > b ? 1 : a < b ? -1 : 0;
  });
};

const DateMultiselect = ({ value, onChange }) => {
  const [selectedDate, handleDateChange] = useState(new Date());

  const handleChange = selected => {
    const formatted = formatDate(selected);
    let freshVal = value ? newValueArray(formatted, [...value]) : [formatted];

    if (freshVal && freshVal.length > 1) {
      freshVal = sortSelected(freshVal);
    }

    onChange(freshVal);
    handleDateChange(selected);
  };

  const renderDayComponent = (
    day,
    selectedDate,
    isInCurrentMonth,
    dayComponent
  ) => {
    const isSelected = value && value.includes(formatDate(day));

    return (
      <div className={`${isSelected ? 'ea-selected' : ''}`}>{dayComponent}</div>
    );
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
            onChange={handleChange}
            renderDay={renderDayComponent}
          />
          <div style={style.dates} />
        </div>
      </FieldSet>
    </MuiPickersUtilsProvider>
  );
};

export default DateMultiselect;
