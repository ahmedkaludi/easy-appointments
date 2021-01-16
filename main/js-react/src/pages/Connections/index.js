import React, { useEffect } from 'react';
import { ConnectionsCommunicator } from '../../communicators/ConnectionsCommunicator';

const Connections = () => {
  useEffect(function mount() {
    ConnectionsCommunicator.fetchAll().then(response => console.log(response));
  }, []);

  return <div>TODO</div>;
};

export default Connections;
