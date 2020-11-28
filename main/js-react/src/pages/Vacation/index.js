import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Sidebar, Loader } from '../../ea-components';
import { VacationsCommunicator } from '../../communicators';
import { VacationForm } from './components/VacationForm';
import { VacationTable } from './components/VacationTable';

const Vacation = () => {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [vacations, setVacations] = useState([]);
  const [activeVacation, setActiveVacation] = useState(null);
  const [processing, setProcessing] = useState(null);

  const loadVacations = async () => {
    try {
      const records = await VacationsCommunicator.fetch();
      setVacations(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };

  useEffect(() => {
    loadVacations();
  }, []);

  const toggleSidebar = () => {
    if (open && activeVacation) {
      setActiveVacation(null);
    }

    setOpen(!open);
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

  const onEdit = async model => {
    try {
      const data = vacations.map(record =>
        record.id === model.id ? model : record
      );
      await VacationsCommunicator.save(data);
      toggleSidebar();
      setVacations(data);
    } catch (e) {
      throw new Error(e);
    }
  };

  const save = (model, isEdit) => (isEdit ? onEdit(model) : onCreate(model));

  const onEditClick = row => {
    setActiveVacation(row);
    toggleSidebar();
  };

  const onDeleteClick = async row => {
    setProcessing(row?.id);

    try {
      const data = vacations.filter(record => record.id !== row.id);
      await VacationsCommunicator.save(data);
      setVacations(data);
    } catch (e) {
      throw new Error(e);
    }

    setProcessing(null);
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
        <VacationTable
          data={vacations}
          onEdit={onEditClick}
          onDelete={onDeleteClick}
          processing={processing}
        />
      )}

      <Sidebar
        title={`${activeVacation ? 'Edit' : 'Add'} vacation`}
        open={open}
        onClose={toggleSidebar}>
        <VacationForm
          model={activeVacation}
          onSave={save}
          onCancel={toggleSidebar}
        />
      </Sidebar>
    </Fragment>
  );
};

export default Vacation;
