import React, { Fragment } from 'react';

import { ReportCards } from './components/ReportCards';
// import { __ } from '../../services/Localization';
import { PageTitle } from '../../ea-components';

const ReportsPage = () => {
  return (
    <Fragment>
      <PageTitle titleHeading="Reports" />
      <ReportCards />
    </Fragment>
  );
};

export default ReportsPage;
