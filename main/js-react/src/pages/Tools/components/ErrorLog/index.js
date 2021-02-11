import React, { Fragment, useState } from 'react';

import { __ } from '../../../../services/Localization';
import { Grid, Button, Popover } from '@material-ui/core';
import Alert from '@material-ui/lab/Alert';
import PerfectScrollbar from 'react-perfect-scrollbar';
import { ContentBox } from '../../../../ea-components';

const ERROR_TYPES = {
  MAIL: { label: __('Mail error', 'easy-appointments') }
};

export const ErrorLog = ({ errors }) => {
  const [anchorEl, setAnchorEl] = useState(null);
  const [pop, setPop] = useState(null);

  const handleClick = (event, content) => {
    setPop(content);
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => setAnchorEl(null);

  const renderError = err => {
    const { label } = ERROR_TYPES[err.error_type];
    const errText = Object.values(JSON.parse(err.errors))[0][0];

    return (
      <Grid item xs={12} sm={6} lg={3}>
        <Alert severity="error">
          <b>{label}</b>
          <span>{errText}</span>
          <div className="divider my-2 bg-dark" />
          <Button onClick={event => handleClick(event, err.errors_data)}>
            {__('Details', 'easy-appointments')}
          </Button>
        </Alert>
      </Grid>
    );
  };

  return errors?.length ? (
    <Fragment>
      <ContentBox customClass="card-header p-3 mb-5">
        <Grid container spacing={2}>
          {errors.map(err => renderError(err))}
        </Grid>
      </ContentBox>

      <Popover
        open={!!anchorEl}
        anchorEl={anchorEl}
        onClose={handleClose}
        anchorOrigin={{
          vertical: 'center',
          horizontal: 'center'
        }}
        transformOrigin={{
          vertical: 'center',
          horizontal: 'center'
        }}
        PaperProps={{
          style: { maxWidth: '300px', maxHeight: '300px' }
        }}>
        <PerfectScrollbar className="p-3">
          <span>{pop}</span>
        </PerfectScrollbar>
      </Popover>
    </Fragment>
  ) : null;
};
