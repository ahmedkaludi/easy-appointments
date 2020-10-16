import React, { Fragment } from 'react';

import { PageTitle } from '../ea-components';
import { EmptyState } from '../ea-components';

// const DATA = window.eaData;

const Vacation = () => {
  const headerAction = {
    callback: () => {},
    icon: 'calendar-plus',
    text: 'Add vacation'
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Vacations" action={headerAction} />
      <EmptyState />
    </Fragment>
  );
};

export { Vacation };
