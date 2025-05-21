import React, { useState, useEffect } from 'react';

import { Button } from '@material-ui/core';
import { __ } from '../../../../services/Localization';

export const Reset = ({ resetPlugin }) => {
  const [message, setMessage] = useState(' ');

  useEffect(() => {
    let tmout = null;
    if (message.length > 1) {
      tmout = setTimeout(() => setMessage(' '), 5000);
    }

    return () => clearTimeout(tmout);
  }, [message]);

  const resetPluginData = async () => {
    const response = await resetPlugin();
    setMessage(response);
  };

  return (
    <div className="align-items-center align-items-md-start flex-sm-column flex-lg-row flex-md-row flex-column">
      <span className="d-block text-black-50 font-size-sm my-3">
        {__(
          'This will erase all plugin settings, data, and custom entries created by the plugin. After reset, the plugin will be restored to its default installation state',
          'easy-appointments'
        )}
      </span>
      <p className="font-size-sm" style={{ color: 'green' }}>
        {message}
      </p>
      <div className="d-flex mt-3 mt-sm-3 mt-md-1">
        <Button
          variant="contained"
          color="primary"
          onClick={() => {
            if (
              window.confirm(
                'Are you sure you want to reset the plugin data? This action cannot be undo.'
              )
            ) {
              resetPluginData();
            }
          }}
          className="mr-1">
          Reset
        </Button>
      </div>
    </div>
  );
};
