import React from 'react';

import { Grid } from '@material-ui/core';

export const ReportCards = () => (
  <div>
    <Grid container spacing={4}>
      <Grid item xs={12} sm={6} md={4}>
        <a
          className="card card-box-hover-rise mb-4"
          href="#/"
          onClick={e => e.preventDefault()}>
          <img alt="..." className="card-img-top" src={null} />
          <div className="card-body card-body-avatar">
            <div className="avatar-icon-wrapper avatar-icon-wrapper--sm">
              <div className="avatar-icon">
                <img alt="..." src={null} />
              </div>
            </div>
            <h5 className="card-title font-weight-bold font-size-lg">
              Time Table
            </h5>
            <p className="card-text">
              Have Calendar overview of all bookings and free slots.
            </p>
          </div>
        </a>
      </Grid>
      <Grid item xs={12} sm={6} md={4}>
        <a
          className="card card-box-hover-rise mb-4"
          href="#/"
          onClick={e => e.preventDefault()}>
          <img alt="..." className="card-img-top" src={null} />
          <div className="card-body card-body-avatar">
            <div className="avatar-icon-wrapper avatar-icon-wrapper--sm">
              <div className="avatar-icon">
                <img alt="..." src={null} />
              </div>
            </div>
            <h5 className="card-title font-weight-bold font-size-lg">Export</h5>
            <p className="card-text">
              Export data in Excel CSV format for selected time period.
            </p>
          </div>
        </a>
      </Grid>
    </Grid>
  </div>
);
