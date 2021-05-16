import React from 'react';

import moment from 'moment';
import { Calendar, momentLocalizer } from 'react-big-calendar';

import 'react-big-calendar/lib/css/react-big-calendar.css';

const localizer = momentLocalizer(moment);

export const TimeTable = () => {
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
    <div>
      <Calendar
        localizer={localizer}
        defaultDate={new Date()}
        defaultView="month"
        events={events}
        style={{ height: '100vh' }}
      />
    </div>
  );
};
