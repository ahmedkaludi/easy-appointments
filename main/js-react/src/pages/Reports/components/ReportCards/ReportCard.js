import React from 'react';
import PropTypes from 'prop-types';

import { Grid } from '@material-ui/core';

export const ReportCard = ({ title, description, action, imgSource }) => (
  <Grid item xs={12} sm={6} md={3}>
    <div
      role="button"
      className="ea-report-card card card-box-hover-rise mt-0"
      onClick={action}>
      <img alt="..." className="card-img-top" src={imgSource?.background} />
      <div className="card-body card-body-avatar">
        <div className="avatar-icon-wrapper avatar-icon-wrapper--sm">
          <div className="avatar-icon">
            <img alt="..." src={imgSource?.avatar} />
          </div>
        </div>
        <h5 className="card-title font-weight-bold font-size-lg">{title}</h5>
        <p className="card-text">{description}</p>
      </div>
    </div>
  </Grid>
);

ReportCard.propTypes = {
  title: PropTypes.string.isRequired,
  description: PropTypes.string.isRequired,
  action: PropTypes.func.isRequired,
  imgSource: PropTypes.objectOf(PropTypes.any)
};
