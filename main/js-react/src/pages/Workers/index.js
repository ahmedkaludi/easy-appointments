import React, { Fragment, useState, useEffect } from 'react';

import { __ } from '../../services/Localization';
import { WorkersForm } from './components/WorkersForm';
import { WorkersTable } from './components/WorkersTable';
import { WorkersCommunicator } from '../../communicators';
import {
  PageTitle,
  Sidebar,
  PageContentWrap,
  PAGE_KEYS
} from '../../ea-components';

const WorkersPage = () => {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [isPro, setIsPro] = useState(false);
  const [displaySignOut, setDisplaySignOut] = useState(false);
  const [workers, setWorkers] = useState([]);
  const [activeWorker, setActiveWorker] = useState(null);
  const [processing, setProcessing] = useState(null);

  const loadWorkers = async () => {
    try {
      const records = await WorkersCommunicator.fetchAll();
      setWorkers(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };
  const isProPluginExist = async () => {
    try {
      const records = await WorkersCommunicator.isProExist();
      setIsPro(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };

  useEffect(() => {
    loadWorkers();
    isProPluginExist();
  }, []);

  const toggleSidebar = () => {
    if (open && activeWorker) {
      setActiveWorker(null);
    }

    setOpen(!open);
  };

  const onCreate = async model => {
    try {
      const result = await WorkersCommunicator.save(model);
      toggleSidebar();
      setWorkers([{ ...model, id: result.id }, ...workers]);
    } catch (e) {
      throw new Error(e);
    }
  };

  const onEdit = async model => {
    try {
      await WorkersCommunicator.save(model);

      const data = workers.map(record =>
        record.id === model.id ? model : record
      );
      toggleSidebar();
      setWorkers(data);
    } catch (e) {
      throw new Error(e);
    }
  };

  const save = (model, isEdit) => (isEdit ? onEdit(model) : onCreate(model));

  const onEditClick = row => {
    if (isPro) {
      haveGoogleToken(row.id);
    }
    setActiveWorker(row);
    toggleSidebar();
  };

  const onDeleteClick = async row => {
    const confirmDelete = window.confirm(
      `Are you sure you want to delete "${row.name}"?`
    );
    if (!confirmDelete) return;

    setProcessing(row?.id);

    try {
      const data = workers.filter(record => record.id !== row.id);
      const result = await WorkersCommunicator.delete(row.id);

      if (result) {
        setWorkers(data);
      }
    } catch (e) {
      console.error('Delete failed:', e);
    }

    setProcessing(null);
  };

  const onGoogleCalendarSignOut = async id => {
    setProcessing(id);
    try {
      await WorkersCommunicator.googleSignOut(id);
      toggleSidebar();
    } catch (e) {
      throw new Error(e);
    }
    setProcessing(null);
  };

  const haveGoogleToken = async id => {
    setProcessing(true);
    try {
      const result = await WorkersCommunicator.checkGoogleToken(id);
      setDisplaySignOut(result);
    } catch (e) {
      throw new Error(e);
    }
    setProcessing(null);
  };

  const headerAction = {
    callback: toggleSidebar,
    icon: 'user-plus',
    text: __('Add employee', 'easy-appointments')
  };

  const title = activeWorker
    ? __('Edit employee', 'easy-appointments')
    : __('Add employee', 'easy-appointments');

  return (
    <Fragment>
      <PageTitle
        titleHeading={__('Employees', 'easy-appointments')}
        action={headerAction}
      />

      <PageContentWrap
        pageKey={PAGE_KEYS.WORKERS}
        loading={loading}
        condition={workers.length}>
        <WorkersTable
          data={workers}
          onEdit={onEditClick}
          onDelete={onDeleteClick}
          onSort={loadWorkers}
          processing={processing}
        />
      </PageContentWrap>

      <Sidebar title={title} open={open} onClose={toggleSidebar}>
        <WorkersForm
          model={activeWorker}
          onSave={save}
          onCancel={toggleSidebar}
          isPro={isPro}
          onSignOut={onGoogleCalendarSignOut}
          showSignOut={displaySignOut}
        />
      </Sidebar>
    </Fragment>
  );
};

export default WorkersPage;
