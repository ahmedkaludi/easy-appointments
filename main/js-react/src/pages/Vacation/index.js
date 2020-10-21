import React, { Fragment, useState } from 'react';

import { PageTitle, EmptyState, Sidebar } from '../../ea-components';
import { VacationForm } from './components/VacationForm';
// const DATA = window.eaData;

const Vacation = () => {
  const [open, setOpen] = useState(false);

  const toggleSidebar = () => setOpen(!open);

  const headerAction = {
    callback: toggleSidebar,
    icon: 'calendar-plus',
    text: 'Add vacation'
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Vacations" action={headerAction} />
      <EmptyState
        type="vacation"
        message="There are no scheduled vacation days yet."
        hint={`Use the 'Add vacation' button to add new vacation days.`}
      />
      <Sidebar open={open} onClose={toggleSidebar} title="Add vacation">
        <VacationForm onSave={toggleSidebar} onCancel={toggleSidebar} />
      </Sidebar>
    </Fragment>
  );
};

export default Vacation;
