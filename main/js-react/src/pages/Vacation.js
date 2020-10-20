import React, { Fragment, useState } from 'react';

import { PageTitle, EmptyState, Sidebar, Form } from '../ea-components';

// const DATA = window.eaData;

const Vacation = () => {
  const [open, setOpen] = useState(false);

  const toggleSidebar = () => setOpen(!open);

  const headerAction = {
    callback: toggleSidebar,
    icon: 'calendar-plus',
    text: 'Add vacation'
  };

  const renderVacationForm = () => (
    <Form>
      <span>Sidebar body</span>
    </Form>
  );

  return (
    <Fragment>
      <PageTitle titleHeading="Vacations" action={headerAction} />
      <EmptyState
        type="vacation"
        message="There are no scheduled vacation days yet."
        hint={`Use the 'Add vacation' button to add new vacation days.`}
      />
      <Sidebar open={open} onClose={toggleSidebar} title="Add vacation">
        {renderVacationForm()}
      </Sidebar>
    </Fragment>
  );
};

export { Vacation };
