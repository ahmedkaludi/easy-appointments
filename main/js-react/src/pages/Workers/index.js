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

  useEffect(() => {
    loadWorkers();
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
    setActiveWorker(row);
    toggleSidebar();
  };

  const onDeleteClick = async row => {
    setProcessing(row?.id);

    try {
      const data = workers.filter(record => record.id !== row.id);
      const result = await WorkersCommunicator.delete(row.id);

      if (result) {
        setWorkers(data);
      }
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
        />
      </Sidebar>
    </Fragment>
  );
};

export default WorkersPage;
