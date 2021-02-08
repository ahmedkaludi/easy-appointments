import React, { useState } from 'react';

import { Button, TextField } from '@material-ui/core';
import { ToolsCommunicator } from '../../../../communicators/ToolsCommunicator';

export const TestMail = () => {
  const { value, setValue } = useState('');

  const sendMail = native => {
    ToolsCommunicator.testEmail(value, native).then();
  };

  const onChange = event => {
    setValue(event.current.value);
  };

  return (
    <div className="d-flex align-items-center flex-sm-column flex-lg-row flex-md-row flex-column">
      <TextField
        label="Email address"
        variant="outlined"
        size="small"
        className="standalone"
        style={{ width: '300px' }}
        value={value}
        onChange={onChange}
      />
      <div className="d-flex mt-3 mt-sm-3 mt-md-0">
        <Button
          onClick={() => sendMail('0')}
          variant="contained"
          color="primary"
          className="mr-1 ml-2">
          Send a test email
        </Button>
        <Button
          onClick={() => sendMail('1')}
          variant="contained"
          color="primary"
          className="mx-1">
          Send a test email (native)
        </Button>
      </div>
    </div>
  );
};
