import React from 'react';

import moment from 'moment';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import Button from '@material-ui/core/Button';
import FilterListIcon from '@material-ui/icons/FilterList';
import Divider from '@material-ui/core/Divider';
import { ContentBox } from '../../../../ea-components';
import { TimetableFilter } from './components/TimetableFilter';
import { FilterChips } from './components/FilterChips';

const localizer = momentLocalizer(moment);

export const TimeTable = () => {
  const [open, setOpen] = React.useState(false);
  const [filters, setFilters] = React.useState({});

  const onChange = obj => {
    setFilters(obj);
    setOpen(false);
  };

  const events = [
    {
      start: moment().toDate(),
      end: moment()
        .add(1, 'days')
        .toDate(),
      title: 'Some title'
    }
  ];

  return (
    <ContentBox customClass="mb-0 p-3">
      <div className="d-flex align-items-center">
        <Button onClick={() => setOpen(true)} className="px-2">
          <FilterListIcon />
          {!Object.keys(filters).length ? 'Add filters' : 'Edit filters'}
        </Button>
        {!!Object.keys(filters).length && (
          <Divider orientation="vertical" className="mx-2" flexItem />
        )}
        <FilterChips filters={filters} onChange={setFilters} disabled={open} />
      </div>
      <Divider variant="middle" className="my-2 mx-0" />
      <div className="ea-timetable">
        <TimetableFilter open={open} filters={filters} onChange={onChange} />
        <div className={`calendar-wrap ${open ? 'open' : ''}`}>
          <Calendar
            localizer={localizer}
            defaultDate={new Date()}
            defaultView="month"
            events={events}
            style={{ height: '100vh' }}
          />
        </div>
      </div>
    </ContentBox>
  );
};
