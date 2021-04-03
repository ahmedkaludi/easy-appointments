import React from 'react';

import { Grid } from '@material-ui/core';
import { ReportCard } from './ReportCard';
import { BACKGROUNDS } from './ReportBackgrounds';

export const ReportCards = () => (
  <div className="ea-margin-fix mt-4">
    <Grid container spacing={3}>
      <ReportCard
        title="Time Table"
        description="Have Calendar overview of all bookings and free slots."
        action={f => f}
        imgSource={BACKGROUNDS.CALENDAR}
      />
      <ReportCard
        title="Export"
        description="Export data in Excel CSV format for selected time period."
        action={f => f}
        imgSource={BACKGROUNDS.EXCEL}
      />
    </Grid>
  </div>
);
