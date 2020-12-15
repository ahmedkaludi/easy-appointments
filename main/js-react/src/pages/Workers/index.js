import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Loader, Sidebar } from '../../ea-components';
import { WorkersCommunicator } from '../../communicators';
import { WorkersForm } from './components/WorkersForm';
import { WorkersTable } from './components/WorkersTable';

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
    text: 'Add employee'
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Employees" action={headerAction} />

      {loading ? (
        <Loader text="Loading employees" />
      ) : !workers.length ? (
        <EmptyState
          type="worker"
          message="There are no employees yet."
          hint={`Use the 'Add employee' button to add new employee.`}
        />
      ) : (
        <WorkersTable
          data={workers}
          onEdit={onEditClick}
          onDelete={onDeleteClick}
          processing={processing}
        />
      )}

      <Sidebar
        title={`${activeWorker ? 'Edit' : 'Add'} employee`}
        open={open}
        onClose={toggleSidebar}>
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
