import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Sidebar, Loader } from '../../ea-components';
import { VacationsCommunicator } from '../../communicators';
import { VacationForm } from './components/VacationForm';
import { VacationTable } from './components/VacationTable';

const Vacation = () => {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [vacations, setVacations] = useState([]);

  const toggleSidebar = () => setOpen(!open);

  const loadVacations = async () => {
    try {
      const records = await VacationsCommunicator.fetch();
      setVacations(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };

  const onCreate = async model => {
    try {
      await VacationsCommunicator.save([model, ...vacations]);
      toggleSidebar();
      setVacations([model, ...vacations]);
    } catch (e) {
      throw new Error(e);
    }
  };

  useEffect(() => {
    loadVacations();
  }, []);

  const save = model => {
    onCreate(model);
  };

  const headerAction = {
    callback: toggleSidebar,
    icon: 'calendar-plus',
    text: 'Add vacation'
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Vacations" action={headerAction} />

      {loading ? (
        <Loader text="Loading vacations" />
      ) : !vacations.length ? (
        <EmptyState
          type="vacation"
          message="There are no scheduled vacation days yet."
          hint={`Use the 'Add vacation' button to add new vacation days.`}
        />
      ) : (
        <VacationTable data={vacations} />
      )}

      <Sidebar open={open} onClose={toggleSidebar} title="Add vacation">
        <VacationForm onSave={save} onCancel={toggleSidebar} />
      </Sidebar>
    </Fragment>
  );
};

export default Vacation;
