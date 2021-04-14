import React from 'react';
import PropTypes from 'prop-types';

import { Grid } from '@material-ui/core';
import { ReportCard } from './ReportCard';
import { BACKGROUNDS } from './ReportBackgrounds';

export const ReportCards = ({ onChange }) => (
  <div className="ea-margin-fix mt-4">
    <Grid container spacing={3}>
      <ReportCard
        title="Time Table"
        description="Have Calendar overview of all bookings and free slots."
        action={() => onChange('timetable')}
        imgSource={BACKGROUNDS.CALENDAR}
      />
      <ReportCard
        title="Export"
        description="Export data in Excel CSV format for selected time period."
        action={() => onChange('export')}
        imgSource={BACKGROUNDS.EXCEL}
      />
    </Grid>
  </div>
);

ReportCards.propTypes = {
  onChange: PropTypes.func.isRequired
};
