import React, { Fragment, useState, useEffect } from 'react';

import { __ } from '../../services/Localization';
import { VacationForm } from './components/VacationForm';
import { VacationTable } from './components/VacationTable';
import { VacationsCommunicator } from '../../communicators';
import {
  PageTitle,
  Sidebar,
  PageContentWrap,
  PAGE_KEYS
} from '../../ea-components';

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
    text: __('Add vacation', 'easy-appointments')
  };

  const title = activeVacation
    ? __('Edit vacation', 'easy-appointments')
    : __('Add vacation', 'easy-appointments');

  return (
    <Fragment>
      <PageTitle
        titleHeading={__('Vacations', 'easy-appointments')}
        action={headerAction}
      />

      <PageContentWrap
        pageKey={PAGE_KEYS.VACATIONS}
        loading={loading}
        condition={vacations.length}>
        <VacationTable
          data={vacations}
          onEdit={onEditClick}
          onDelete={onDeleteClick}
          processing={processing}
        />
      </PageContentWrap>

      <Sidebar title={title} open={open} onClose={toggleSidebar}>
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
