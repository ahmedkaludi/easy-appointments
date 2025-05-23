import React, { Fragment, useState, useEffect } from 'react';

import { __ } from '../../services/Localization';
import { Button } from '@material-ui/core';
import ErrorOutlineIcon from '@material-ui/icons/ErrorOutline';
import MailOutlineIcon from '@material-ui/icons/MailOutline';
import RefreshIcon from '@material-ui/icons/Refresh';
import { ToolsCommunicator } from '../../communicators/ToolsCommunicator';
import {
  PageTitle,
  ContentBox,
  PageContentWrap,
  PAGE_KEYS
} from '../../ea-components';
import { ErrorLog } from './components/ErrorLog';
import { TestMail } from './components/TestMail';
import { Reset } from './components/Reset';

const Tools = () => {
  const [loading, setLoading] = useState(true);
  const [errors, setErrors] = useState([]);

  useEffect(function mount() {
    ToolsCommunicator.fetchAllErrors().then(errs => {
      setErrors(errs);
      setLoading(false);
    });
  }, []);

  const clearLogs = () => {
    ToolsCommunicator.clearLogs().then(() => {
      setErrors([]);
    });
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

        <div className="divider my-2" />

        <span className="d-block text-black-50 font-size-sm my-3">
          {__(
            'Test if the mail service is working fine on this site by generating a test email that will be sent to provided address.',
            'easy-appointments'
          )}
        </span>
        <TestMail test={ToolsCommunicator.testEmail} />
      </ContentBox>
      <ContentBox customClass="card-header p-3 mb-5">
        <div className="d-flex align-items-center">
          <RefreshIcon className="ea-text" />
          <span className="card-header--title font-size-md ml-2">
            {__('Reset Plugin', 'easy-appointments')}
          </span>
        </div>

        <div className="divider my-2" />

        <Reset resetPlugin={ToolsCommunicator.resetPlugin} />
      </ContentBox>

      <div className="d-flex mb-3 ea-margin-fix align-items-center justify-content-between">
        <h5 className="m-0 display-5 d-flex align-items-center">
          <ErrorOutlineIcon className="text-warning mr-2" />
          {__('Error log', 'easy-appointments')}
        </h5>
        {errors.length ? (
          <Button onClick={clearLogs} variant="outlined" color="primary">
            {__('Clear log', 'easy-appointments')}
          </Button>
        ) : null}
      </div>

      <PageContentWrap
        pageKey={PAGE_KEYS.ERROR_LOG}
        loading={loading}
        condition={errors.length}>
        <ErrorLog errors={errors} />
      </PageContentWrap>
    </Fragment>
  );
};

export default Tools;
