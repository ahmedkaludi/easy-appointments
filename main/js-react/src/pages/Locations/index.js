import React, { Fragment, useState } from 'react';

import { PageTitle, EmptyState, Loader } from '../../ea-components';

const LocationsPage = () => {
  const [loading] = useState(true);
  const [locations] = useState([]);

  const headerAction = {
    callback: f => f,
    icon: 'calendar-plus',
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
