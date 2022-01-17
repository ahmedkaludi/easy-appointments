import React from 'react';
import moment from 'moment';
import Button from '@material-ui/core/Button';
import Divider from '@material-ui/core/Divider';
import { ContentBox } from '../../../../ea-components';
import { FilterChips } from './components/FilterChips';
import FilterListIcon from '@material-ui/icons/FilterList';
import { TimetableFilter } from './components/TimetableFilter';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import Select from '../../../../ea-components/FormFields/Select';
import { FullCalendarCommunicator } from '../../../../communicators/FullCalendarCommunicator';

const localizer = momentLocalizer(moment);

/**
 * Simple wrapper to get DateTime format for ajax call
 * @param date
 * @returns {string}
 */
const parseDate = date => moment(date).format('YYYY-MM-DD');

/**
 * Single Event Component
 *
 * @param event
 * @returns {JSX.Element}
 * @constructor
 */
function Event({ event }) {
  const className = event?.status
    ? `event-content status-${event.status}`
    : 'event-content';
  return (
    <div className={className} style={{ backgroundColor: event.color }}>
      {event.id && <span>{`#${event.id} ${event.title}`}</span>}
      {!event.id && <span>{event.title}</span>}
    </div>
  );
}

/**
 * Get all available options as labels
 *
 * @returns {*}
 */
function getEventLabelOptions() {
  // eslint-disable-next-line no-undef
  return eaData.MetaFields.map(option => ({
    value: option.slug,
    label: option.label
  }));
}

/**
 * We need first label as default
 */
const defaultEventLabel = getEventLabelOptions().shift().value;

/**
 * Main component for calendar
 *
 * @returns {JSX.Element}
 * @constructor
 */
export const TimeTable = () => {
  const [calendarState, setCalendarState] = React.useState(null);
  const [open, setOpen] = React.useState(false);
  const [events, setEvents] = React.useState([]);
  const [filters, setFilters] = React.useState({});
  const [eventLabel, setEventLabel] = React.useState(defaultEventLabel);

  /**
   * Main action for loading events
   */
  const loadEventsAction = () => {
    let from = null;
    let to = null;

    if (Array.isArray(calendarState)) {
      const first = 0;
      const last = calendarState.length - 1;

      from = parseDate(calendarState[first]);
      to = parseDate(calendarState[last]);
    }

    if (!Array.isArray(calendarState) && calendarState?.start) {
      from = parseDate(calendarState.start);
      to = parseDate(calendarState.end);
    }

    if (calendarState === null) {
      from = moment()
        .clone()
        .startOf('month')
        .format('YYYY-MM-DD');
      to = moment()
        .clone()
        .endOf('month')
        .format('YYYY-MM-DD');
    }

    FullCalendarCommunicator.fetchEvents(
      from,
      to,
      eventLabel,
      filters
    ).then(events => setEvents(events));
  };

  /**
   * Set filters
   * @param obj
   */
  const onChange = obj => {
    setFilters(obj);
    setOpen(false);
  };

  /**
   * New selection inside calendar or view changed
   * @param calState
   */
  const onCalendarChange = calState => {
    setCalendarState(calState);
  };

  const onEventLabelChange = selected => {
    const option = getEventLabelOptions().find(o => o.value === selected);
    setEventLabel(option.value);
  };

  React.useEffect(loadEventsAction, [calendarState, filters, eventLabel]);

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
        <Divider orientation="vertical" className="mx-2" flexItem />
        <div style={{ width: '20%' }}>
          <Select
            label="Select Event label"
            value={eventLabel}
            options={getEventLabelOptions()}
            onChange={event => onEventLabelChange(event.target.value)}
          />
        </div>
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
            onRangeChange={onCalendarChange}
            components={{ event: Event }}
          />
        </div>
      </div>
    </ContentBox>
  );
};
