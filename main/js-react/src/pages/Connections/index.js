import React, { Fragment, useEffect, useState } from 'react';

import { __ } from '../../services/Localization';
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

      <Sidebar title={title} open={open} onClose={toggleSidebar} />
    </Fragment>
  );
};

export default ConnectionsPage;
