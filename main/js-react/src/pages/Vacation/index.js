import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Sidebar } from '../../ea-components';
import { VacationForm } from './components/VacationForm';

// TODO put in separate communicator
const vacationUrl = window.ea_settings.rest_url_vacation;
const jQuery = window.jQuery;
const nonce = window.wpApiSettings.nonce;
const url = `${vacationUrl}?_wpnonce=${nonce}`;

const Vacation = () => {
  const [open, setOpen] = useState(false);
  const [vacations, setVacations] = useState([]);

  const toggleSidebar = () => setOpen(!open);

  const loadVacations = () => {
    jQuery.getJSON(url).then(result => setVacations(result));
  };

  useEffect(() => {
    loadVacations();
  }, []);

  const save = model => {
    return jQuery.post(url, JSON.stringify([model, ...vacations])).then(() => {
      toggleSidebar();
      setVacations([model, ...vacations]);
    });
  };

  const headerAction = {
    callback: toggleSidebar,
    icon: 'calendar-plus',
    text: 'Add vacation'
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Vacations" action={headerAction} />
      {/* TODO initial loader */}
      {vacations.length === 0 && (
        <EmptyState
          type="vacation"
          message="There are no scheduled vacation days yet."
          hint={`Use the 'Add vacation' button to add new vacation days.`}
        />
      )}
      {vacations.map(vacation => (
        <div>{vacation.title}</div>
      ))}
      <Sidebar open={open} onClose={toggleSidebar} title="Add vacation">
        <VacationForm onSave={save} onCancel={toggleSidebar} />
      </Sidebar>
    </Fragment>
  );
};

export default Vacation;
