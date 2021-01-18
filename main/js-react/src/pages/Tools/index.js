import React, { Fragment, useEffect } from 'react';

// import { __ } from '../../services/Localization';
import { ToolsCommunicator } from '../../communicators/ToolsCommunicator';
import { PageTitle } from '../../ea-components';

const Tools = () => {
  useEffect(function mount() {
    ToolsCommunicator.fetchAllErrors().then(errors => console.log(errors));
  }, []);

  return (
    <Fragment>
      <PageTitle titleHeading="Tools" />
    </Fragment>
  );
};

export default Tools;
