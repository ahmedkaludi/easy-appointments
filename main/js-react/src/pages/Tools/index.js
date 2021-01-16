import React, { useEffect } from 'react';
import { ToolsCommunicator } from '../../communicators/ToolsCommunicator';

const Tools = () => {
  useEffect(function mount() {
    ToolsCommunicator.fetchAllErrors().then(errors => console.log(errors));
  }, []);

  return <div>TODO</div>;
};

export default Tools;
