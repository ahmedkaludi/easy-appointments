import React, { useState, useMemo } from 'react';

import { __ } from '../../services/Localization';
import { PageTitle } from '../../ea-components';
import { ReportCards } from './components/ReportCards';
import { Export } from './components/Export';
import { TimeTable } from './components/TimeTable';

const ReportsPage = () => {
  const [visible, setVisible] = useState(null);

  const PAGES = useMemo(
    () => ({
      export: <Export />,
      timetable: <TimeTable />
    }),
    []
  );

  const headerAction = useMemo(
    () =>
      visible
        ? {
            callback: () => setVisible(null),
            icon: 'angle-left',
            text: __('Go back', 'easy-appointments')
          }
        : null,
    [visible]
  );

  return (
    <>
      <PageTitle titleHeading="Reports" action={headerAction} />
      {visible ? PAGES[visible] : <ReportCards onChange={setVisible} />}
    </>
  );
};

export default ReportsPage;
