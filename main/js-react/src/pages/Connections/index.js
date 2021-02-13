import React, { Fragment, useEffect, useState } from 'react';

import { __ } from '../../services/Localization';
import { ConnectionsForm } from './components/ConnectionsForm';
import { ConnectionsTable } from './components/ConnectionsTable';
import { ConnectionsCommunicator } from '../../communicators/ConnectionsCommunicator';
import {
  PageTitle,
  Sidebar,
  PageContentWrap,
  PAGE_KEYS
} from '../../ea-components';

const ConnectionsPage = () => {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [connections, setConnections] = useState([]);
  const [activeConnection, setActiveConnection] = useState(null);
  const [processing, setProcessing] = useState(null);

  const loadConnections = async () => {
    try {
      const records = await ConnectionsCommunicator.fetchAll();
      setConnections(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };

  useEffect(() => {
    loadConnections();
  }, []);

  const toggleSidebar = () => {
    if (open && activeConnection) {
      setActiveConnection(null);
    }

    setOpen(!open);
  };

  const onCreate = async model => {
    try {
      const result = await ConnectionsCommunicator.save(model);
      toggleSidebar();
      setConnections([{ ...model, id: result.id }, ...connections]);
    } catch (e) {
      throw new Error(e);
    }
  };

  const onEdit = async model => {
    try {
      await ConnectionsCommunicator.save(model);

      const data = connections.map(record =>
        record.id === model.id ? model : record
      );
      toggleSidebar();
      setConnections(data);
    } catch (e) {
      throw new Error(e);
    }
  };

  const save = (model, isEdit) => (isEdit ? onEdit(model) : onCreate(model));

  const onEditClick = row => {
    setActiveConnection(row);
    toggleSidebar();
  };

  const onDeleteClick = async row => {
    setProcessing(row?.id);

    try {
      const data = connections.filter(record => record.id !== row.id);
      const result = await ConnectionsCommunicator.delete(row.id);

      if (result) {
        setConnections(data);
      }
    } catch (e) {
      throw new Error(e);
    }

    setProcessing(null);
  };

  const onCloneClick = async row => {
    try {
      const copied = { ...row };
      delete copied.id;

      const result = await ConnectionsCommunicator.save(copied);
      setConnections([{ ...copied, id: result.id }, ...connections]);
    } catch (e) {
      throw new Error(e);
    }
  };

  const headerAction = {
    callback: toggleSidebar,
    icon: 'paperclip',
    text: __('Add connection', 'easy-appointments')
  };

  const title = activeConnection
    ? __('Edit connection', 'easy-appointments')
    : __('Add connection', 'easy-appointments');

  return (
    <Fragment>
      <PageTitle titleHeading="Connections" action={headerAction} />

      <PageContentWrap
        pageKey={PAGE_KEYS.CONNECTIONS}
        loading={loading}
        condition={connections.length}>
        <ConnectionsTable
          data={connections}
          onEdit={onEditClick}
          onDelete={onDeleteClick}
          onClone={onCloneClick}
          processing={processing}
        />
      </PageContentWrap>

      <Sidebar title={title} open={open} onClose={toggleSidebar}>
        <ConnectionsForm
          model={activeConnection}
          onSave={save}
          onCancel={toggleSidebar}
        />
      </Sidebar>
    </Fragment>
  );
};

export default ConnectionsPage;
