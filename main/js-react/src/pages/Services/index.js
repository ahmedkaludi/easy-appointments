import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Loader } from '../../ea-components';
import { ServicesCommunicator } from '../../communicators';

const ServicesPage = () => {
  const [loading, setLoading] = useState(true);
  const [services, setServices] = useState([]);

  const loadServices = async () => {
    try {
      const records = await ServicesCommunicator.fetchAll();
      setServices(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };

  useEffect(() => {
    loadServices();
  }, []);

  const headerAction = {
    callback: f => f,
    icon: 'briefcase',
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
