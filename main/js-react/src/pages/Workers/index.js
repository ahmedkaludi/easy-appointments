import React, { Fragment, useState } from 'react';

import { PageTitle, EmptyState, Loader } from '../../ea-components';

const WorkersPage = () => {
  const [loading] = useState(true);
  const [workers] = useState([]);

  const headerAction = {
    callback: f => f,
    icon: 'calendar-plus',
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
