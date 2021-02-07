import React from 'react';

import { Button, TextField } from '@material-ui/core';

export const TestMail = () => (
  <div className="d-flex align-items-center flex-sm-column flex-lg-row flex-md-row flex-column">
    <TextField
      label="Email address"
      variant="outlined"
      size="small"
      className="standalone"
      style={{ width: '300px' }}
    />
    <div className="d-flex mt-3 mt-sm-3 mt-md-0">
      <Button variant="contained" color="primary" className="mr-1 ml-2">
        Send a test email
      </Button>
      <Button variant="contained" color="primary" className="mx-1">
        Send a test email (native)
      </Button>
    </div>
  </div>
);
