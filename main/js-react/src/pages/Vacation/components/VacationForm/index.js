import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { WorkerField } from './fields/WorkerField';
// const DATA = window.eaData;

export const VacationForm = ({ onSave, onCancel }) => (
  <Form onCancel={onCancel} onSave={onSave}>
    <WorkerField />
    <span>Vacation form</span>
  </Form>
);

VacationForm.propTypes = {
  onSave: PropTypes.func,
  onCancel: PropTypes.func
};

VacationForm.defaultProps = {
  onSave: f => f,
  onCancel: f => f
};
