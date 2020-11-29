import React from 'react';
import PropTypes from 'prop-types';

import { Form } from '../../../../ea-components';
import { TitleField } from './fields/TitleField';
import { TooltipField } from './fields/TooltipField';
import { WorkersField } from './fields/WorkersField';
import { DatesField } from './fields/DatesField';

export const VacationForm = ({ model, onSave, onCancel }) => (
  <Form model={model} onCancel={onCancel} onSave={onSave}>
    <TitleField />
    <TooltipField />
    <WorkersField />
    <DatesField />
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
