import React, { useState } from 'react';

import PerfectScrollbar from 'react-perfect-scrollbar';
import DateFnsUtils from '@date-io/date-fns';
import { MuiPickersUtilsProvider, DatePicker } from '@material-ui/pickers';
import { Chip } from '@material-ui/core';
import FieldSet from '../FieldSet';

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
    a = a.split('-').join('');
    b = b.split('-').join('');

    return a > b ? 1 : a < b ? -1 : 0;
  });
};

const DateMultiselect = ({ value, onChange, error }) => {
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

  const onChipDelete = dateChip => {
    const freshVal = newValueArray(dateChip, [...value]);
    onChange(freshVal);
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

  const renderChips = () => (
    <ul>
      {value.map(val => (
        <li>
          <Chip
            label={val}
            onDelete={() => onChipDelete(val)}
            color="primary"
            variant="outlined"
            size="small"
          />
        </li>
      ))}
    </ul>
  );

  return (
    <MuiPickersUtilsProvider utils={DateFnsUtils}>
      <FieldSet label="Dates" error={error}>
        <div className="datepicker-chips-wrapper">
          <DatePicker
            className="ea-date-multiselect"
            disableToolbar
            variant="static"
            value={selectedDate}
            onChange={handleChange}
            renderDay={renderDayComponent}
          />
          <PerfectScrollbar className="dates-chips">
            {value ? renderChips() : null}
          </PerfectScrollbar>
        </div>
      </FieldSet>
    </MuiPickersUtilsProvider>
  );
};

export default DateMultiselect;
