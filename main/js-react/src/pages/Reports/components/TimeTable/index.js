import React from 'react';

import moment from 'moment';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import Drawer from '@material-ui/core/Drawer';
import { ContentBox } from '../../../../ea-components';
import { TimetableFilter } from './TimetableFilter';

const localizer = momentLocalizer(moment);

export const TimeTable = () => {
  const [open, setOpen] = React.useState(false);

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
      <span role="button" onClick={() => setOpen(!open)}>
        CLICK
      </span>
      <div className="divider my-2" />
      <div className="ea-timetable">
        <Drawer
          className="drawer"
          variant="persistent"
          anchor="left"
          open={open}>
          <TimetableFilter />
        </Drawer>
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
