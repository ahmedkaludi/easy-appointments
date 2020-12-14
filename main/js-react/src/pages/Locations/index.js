import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Loader, Sidebar } from '../../ea-components';
import { LocationsCommunicator } from '../../communicators';
import { LocationsForm } from './components/LocationsForm';
import { LocationsTable } from './components/LocationsTable';

const LocationsPage = () => {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [locations, setLocations] = useState([]);
  const [activeLocation, setActiveLocation] = useState(null);
  const [processing, setProcessing] = useState(null);

  const loadLocations = async () => {
    try {
      const records = await LocationsCommunicator.fetchAll();
      setLocations(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };

  useEffect(() => {
    loadLocations();
  }, []);

  const toggleSidebar = () => {
    if (open && activeLocation) {
      setActiveLocation(null);
    }

    setOpen(!open);
  };

  const onCreate = async model => {
    try {
      const result = await LocationsCommunicator.save(model);
      toggleSidebar();
      setLocations([{ ...model, id: result.id }, ...locations]);
    } catch (e) {
      throw new Error(e);
    }
  };

  const onEdit = async model => {
    try {
      await LocationsCommunicator.save(model);

      const data = locations.map(record =>
        record.id === model.id ? model : record
      );
      toggleSidebar();
      setLocations(data);
    } catch (e) {
      throw new Error(e);
    }
  };

  const save = (model, isEdit) => (isEdit ? onEdit(model) : onCreate(model));

  const onEditClick = row => {
    setActiveLocation(row);
    toggleSidebar();
  };

  const onDeleteClick = async row => {
    setProcessing(row?.id);

    try {
      const data = locations.filter(record => record.id !== row.id);
      const result = await LocationsCommunicator.delete(row.id);

      if (result) {
        setLocations(data);
      }
    } catch (e) {
      throw new Error(e);
    }

    setProcessing(null);
  };

  const headerAction = {
    callback: toggleSidebar,
    icon: 'map-marker-alt',
    text: 'Add location'
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Locations" action={headerAction} />

      {loading ? (
        <Loader text="Loading locations" />
      ) : !locations.length ? (
        <EmptyState
          type="location"
          message="There are no available locations yet."
          hint={`Use the 'Add location' button to add new location.`}
        />
      ) : (
        <LocationsTable
          data={locations}
          onEdit={onEditClick}
          onDelete={onDeleteClick}
          processing={processing}
        />
      )}

      <Sidebar
        title={`${activeLocation ? 'Edit' : 'Add'} location`}
        open={open}
        onClose={toggleSidebar}>
        <LocationsForm
          model={activeLocation}
          onSave={save}
          onCancel={toggleSidebar}
        />
      </Sidebar>
    </Fragment>
  );
};

export default LocationsPage;
