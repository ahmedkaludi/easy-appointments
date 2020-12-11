import React, { Fragment, useState } from 'react';

import { PageTitle, EmptyState, Loader } from '../../ea-components';

const ServicesPage = () => {
  const [loading] = useState(true);
  const [services] = useState([]);

  const headerAction = {
    callback: f => f,
    icon: 'calendar-plus',
    text: 'Add service'
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Services" action={headerAction} />

      {loading ? (
        <Loader text="Loading services" />
      ) : !services.length ? (
        <EmptyState
          type="service"
          message="There are no available services yet."
          hint={`Use the 'Add service' button to add new service.`}
        />
      ) : null}
    </Fragment>
  );
};

export default ServicesPage;
