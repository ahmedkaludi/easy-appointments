import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { TitleField } from './fields/TitleField';
import { TooltipField } from './fields/TooltipField';
import { WorkersField } from './fields/WorkersField';

export const VacationForm = ({ onSave, onCancel }) => (
  <Form onCancel={onCancel} onSave={onSave}>
    <TitleField />
    <TooltipField />
    <WorkersField />
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
