import React, { Fragment } from 'react';

import { PageTitle } from '../layout-components';

// const DATA = window.eaData;

const Vacation = () => {
  return (
    <Fragment>
      <PageTitle
        titleHeading="Vacation manager"
        titleDescription="Manage workers' vacation days"
      />
      <span>Beginning of Vacation page</span>
    </Fragment>
  );
};

export { Vacation };
