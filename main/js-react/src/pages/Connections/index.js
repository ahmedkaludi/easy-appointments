import React, { Fragment, useEffect, useState } from 'react';

import { __ } from '../../services/Localization';
import { ConnectionsForm } from './components/ConnectionsForm';
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
  // const [processing, setProcessing] = useState(null);

  const loadConnections = async () => {
    try {
      const records = await ConnectionsCommunicator.fetchAll();
      setConnections(records);
      setLoading(false);
      console.log(records);
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

  const onCreate = () => {};

  const onEdit = () => {};

  const save = (model, isEdit) => (isEdit ? onEdit(model) : onCreate(model));

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
        <span>Connections loaded</span>
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
