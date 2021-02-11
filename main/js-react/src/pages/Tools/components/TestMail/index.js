import React, { useState, useEffect } from 'react';

import { Button, TextField } from '@material-ui/core';
import { __ } from '../../../../services/Localization';

export const TestMail = ({ test }) => {
  const [mail, setMail] = useState('');
  const [message, setMessage] = useState(' ');

  useEffect(() => {
    let tmout = null;
    if (message.length > 1) {
      tmout = setTimeout(() => setMessage(' '), 5000);
    }

    return () => clearTimeout(tmout);
  }, [message]);

  const onChange = event => {
    if (message.length > 1) {
      setMessage(' ');
    }
    setMail(event.target.value);
  };

  const sendMail = async (native = '0') => {
    const response = await test(mail, native);
    setMessage(response);
    setMail('');
  };

  return (
    <div className="d-flex align-items-center align-items-md-start flex-sm-column flex-lg-row flex-md-row flex-column">
      <TextField
        label={__('Email address', 'easy-appointments')}
        variant="outlined"
        size="small"
        className="ea-standalone ea-text-field"
        style={{ width: '300px' }}
        value={mail}
        onChange={onChange}
        helperText={message}
      />
      <div className="d-flex mt-3 mt-sm-3 mt-md-1">
        <Button
          variant="contained"
          color="primary"
          onClick={() => sendMail()}
          className="mr-1 ml-2">
          Send a test email
        </Button>
        <Button
          variant="contained"
          color="primary"
          onClick={() => sendMail('1')}
          className="mx-1">
          Send a test email (native)
        </Button>
      </div>
    </div>
  );
};
