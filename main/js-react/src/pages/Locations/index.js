import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Loader } from '../../ea-components';
import { LocationsCommunicator } from '../../communicators';

const LocationsPage = () => {
  const [loading, setLoading] = useState(true);
  const [locations, setLocations] = useState([]);

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

  const headerAction = {
    callback: f => f,
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
      ) : null}
    </Fragment>
  );
};

export default LocationsPage;
