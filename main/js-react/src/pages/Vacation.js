import React, { Fragment } from 'react';

import { PageTitle } from '../ea-components';

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
      <span>Beginning of Vacation page</span>
    </Fragment>
  );
};

export { Vacation };
