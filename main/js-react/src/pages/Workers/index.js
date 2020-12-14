import React, { Fragment, useState, useEffect } from 'react';

import { PageTitle, EmptyState, Loader } from '../../ea-components';
import { WorkersCommunicator } from '../../communicators';

const WorkersPage = () => {
  const [loading, setLoading] = useState(true);
  const [workers, setWorkers] = useState([]);

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

  const headerAction = {
    callback: f => f,
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
      ) : null}
    </Fragment>
  );
};

export default WorkersPage;
