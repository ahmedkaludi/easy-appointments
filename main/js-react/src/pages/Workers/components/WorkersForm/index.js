import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { NameField } from './fields/NameField';
import { DescriptionField } from './fields/DescriptionField';
import { EmailField } from './fields/EmailField';
import { PhoneField } from './fields/PhoneField';
// import Button from '../../../../ea-components/FormFields/Button';

export const WorkersForm = ({
  model,
  onSave,
  onCancel,
  onLinkGoogleCalendar
}) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <NameField />
    <DescriptionField />
    <EmailField />
    <PhoneField />
    {model && model.id && (
      <div>
        <a
          href={`?init_google_employee=true&employ_id_google=${model.id}`}
          target="_blank"
          rel="noopener noreferrer" className='button button-primary'>
          Link Google Calendar
        </a>
        <button
        className='button'>
          Sign Out
        </button>
      </div>
    )}
  </Form>
);

WorkersForm.propTypes = {
  onSave: PropTypes.func,
  onCancel: PropTypes.func,
  onLinkGoogleCalendar: PropTypes.func
};

WorkersForm.defaultProps = {
  onSave: f => f,
  onCancel: f => f,
  onLinkGoogleCalendar: f => f
};
