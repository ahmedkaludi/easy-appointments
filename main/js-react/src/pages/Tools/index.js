import React, { Fragment, useState, useEffect } from 'react';

import { __ } from '../../services/Localization';
import { Grid, Paper } from '@material-ui/core';
import ErrorOutlineIcon from '@material-ui/icons/ErrorOutline';
import MailOutlineIcon from '@material-ui/icons/MailOutline';
import { ToolsCommunicator } from '../../communicators/ToolsCommunicator';
import { PageTitle, ContentBox } from '../../ea-components';

const ERROR_TYPES = {
  MAIL: { label: 'Mail error', icon: MailOutlineIcon }
};

const Tools = () => {
  const [errors, setErrors] = useState([]);

  useEffect(function mount() {
    ToolsCommunicator.fetchAllErrors().then(errs => {
      setErrors(errs);
    });
  }, []);

  console.log('Errors', errors);

  const renderError = err => {
    const { errors } = err;
    const { icon, label } = ERROR_TYPES[err.error_type];
    const ErrIcon = icon;
    const errText = Object.values(JSON.parse(errors))[0][0];

    return (
      <Grid item xs={12} sm={6} lg={3}>
        <Paper square elevation={1} className="p-4 mb-4">
          <div className="d-flex align-items-center">
            <ErrIcon className="text-warning mr-2" />
            <div className="text-danger font-size-sm">
              {__(label, 'easy-appointments')}
            </div>
          </div>
          <div className="divider my-2" />
          <span className="text-black-50 font-size-sm">{errText}</span>
          <div className="divider my-2" />
        </Paper>
      </Grid>
    );
  };

  return (
    <Fragment>
      <PageTitle titleHeading="Tools" />

      <ContentBox customClass="card-header p-3 mb-5">
        <div className="d-flex align-items-center">
          <MailOutlineIcon className="ea-text" />
          <span className="card-header--title font-size-md ml-2">
            {__('Test email', 'easy-appointments')}
          </span>
        </div>
        <span className="d-block text-black-50 font-size-sm my-3">
          Test if the mail service is working fine on this site by generating a
          test email that will be send to provided address.
        </span>
      </ContentBox>

      {errors.length ? (
        <Fragment>
          <h5 className="display-5 mb-4 d-flex align-items-center">
            <ErrorOutlineIcon className="text-warning mr-2" />
            {__('Error log', 'easy-appointments')}
          </h5>

          <div className="ea-content-box">
            <Grid container spacing={4}>
              {errors.map(err => renderError(err))}
            </Grid>
          </div>
        </Fragment>
      ) : null}
    </Fragment>
  );
};

export default Tools;
