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
import { BulkConnectionsForm } from './components/ConnectionsForm/BulkConnectionsForm';
import { orderBy } from 'lodash';
import { Button } from '@material-ui/core';

const ConnectionsPage = () => {
  const [open, setOpen] = useState(false);
  const [isBulk, setIsBulk] = useState(false);
  const [loading, setLoading] = useState(true);
  const [connections, setConnections] = useState([]);
  const [activeConnection, setActiveConnection] = useState(null);
  const [processing, setProcessing] = useState(null);

  // NEW STATES
  const [selectedIds, setSelectedIds] = useState([]);
  const [showBulkDelete, setShowBulkDelete] = useState(false);

  const toggleSelect = (id, allIds = []) => {
    // Select all
    if (id === 'ALL') {
      setSelectedIds(allIds);
      setShowBulkDelete(true);
      return;
    }

    // Unselect all
    if (id === 'NONE') {
      setSelectedIds([]);
      setShowBulkDelete(false);
      return;
    }

    // Toggle single row
    let updated = [];

    if (selectedIds.includes(id)) {
      updated = selectedIds.filter(item => item !== id);
    } else {
      updated = [...selectedIds, id];
    }

    setSelectedIds(updated);
    setShowBulkDelete(updated.length > 0);
  };

  const deleteSelected = async () => {
    const confirmDelete = window.confirm(
      `Delete ${selectedIds.length} connections?`
    );
    if (!confirmDelete) return;

    try {
      // ONE SINGLE REQUEST
      await ConnectionsCommunicator.deleteMultiple({ ids: selectedIds });

      // Update UI
      setConnections(connections.filter(c => !selectedIds.includes(c.id)));
      setSelectedIds([]);
      setShowBulkDelete(false);
    } catch (e) {
      console.error(e);
    }
  };

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

    if (open && isBulk) {
      setIsBulk(false);
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

  const bulkSave = async model => {
    const { location, service, worker, ...other } = model;
    const cons = [];

    if (!location?.length || !service?.length || !worker?.length) {
      return;
    }

    location.forEach(l => {
      service.forEach(s => {
        worker.forEach(w => {
          cons.push({
            location: l.value,
            service: s.value,
            worker: w.value,
            ...other
          });
        });
      });
    });

    const requests = cons.map(obj => {
      return ConnectionsCommunicator.save(obj).then(result => ({
        ...obj,
        id: result.id
      }));
    });

    const newConnections = await Promise.all(requests);

    setConnections(
      orderBy([...newConnections, ...connections], ['id'], ['desc'])
    );

    toggleSidebar();
  };

  const onEditClick = row => {
    setActiveConnection(row);
    toggleSidebar();
  };

  const onDeleteClick = async row => {
    const confirmDelete = window.confirm(
      `Are you sure you want to delete this?`
    );
    if (!confirmDelete) return;
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

  const headerAction = [
    {
      callback: () => {
        setIsBulk(true);
        toggleSidebar();
      },
      icon: 'plus',
      text: __('Add connections in bulk', 'easy-appointments')
    },
    {
      callback: toggleSidebar,
      icon: 'paperclip',
      text: __('Add connection', 'easy-appointments')
    }
  ];

  const title = isBulk
    ? __('Add connections in bulk', 'easy-appointments')
    : activeConnection
    ? __('Edit connection', 'easy-appointments')
    : __('Add connection', 'easy-appointments');

  const previousYear = new Date().getFullYear() - 1;

  return (
    <Fragment>
      <PageTitle titleHeading="Connections" action={headerAction} />
      {__(
        `Extend connections in bulk that are ending at December 31, ${previousYear} for one more year`,
        'easy-appointments'
      )}
      <Button
        style={{ marginBottom: '5px', marginLeft: '5px' }}
        onClick={() => {
          ConnectionsCommunicator.extendConnection().then(response =>
            alert(response)
          );
        }}
        variant="outlined">
        Extend
      </Button>

      {showBulkDelete && (
        <Button
          variant="contained"
          color="error"
          style={{
            marginBottom: '8px',
            marginLeft: '5px',
            backgroundColor: '#d32f2f',
            color: '#fff'
          }}
          onClick={deleteSelected}>
          Delete Selected
        </Button>
      )}

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
          toggleSelect={toggleSelect}
          selectedIds={selectedIds}
        />
      </PageContentWrap>

      <Sidebar title={title} open={open} onClose={toggleSidebar}>
        {isBulk && (
          <BulkConnectionsForm
            model={{}}
            onBulkSave={bulkSave}
            onCancel={toggleSidebar}
          />
        )}
        {!isBulk && (
          <ConnectionsForm
            model={activeConnection}
            onSave={save}
            onCancel={toggleSidebar}
          />
        )}
      </Sidebar>
    </Fragment>
  );
};

export default ConnectionsPage;
