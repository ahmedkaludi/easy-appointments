// file: ea-blocks/admin/vacations/components/VacationForm/index.js
import React from 'react';
import PropTypes from 'prop-types';
import moment from 'moment';

import { Form } from '../../../../ea-components';
import { TitleField } from './fields/TitleField';
import { TooltipField } from './fields/TooltipField';
import { WorkersField } from './fields/WorkersField';
import { DatesField } from './fields/DatesField';
import { TimeRangeField } from './fields/TimeRangeField';

export const VacationForm = ({ model, onSave, onCancel }) => {
  // Custom form submit with validation
  const handleSave = formData => {
    const { time } = formData;

    // 🧩 Validation logic
    if (time) {
      const { fullDay, startTime, endTime } = time;

      if (!fullDay) {
        if (!startTime || !endTime) {
          alert(
            'Please select both Start Time and End Time for partial-day vacation.'
          );
          return;
        }

        if (!moment(startTime).isBefore(moment(endTime))) {
          alert('End Time must be after Start Time.');
          return;
        }
      }
    }

    const payload = {
      ...(model || {}), // keeps id when editing
      ...formData
    };
    onSave(payload);
  };

  return (
    <Form model={model} onCancel={onCancel} onSave={handleSave} customId>
      <TitleField />
      <TooltipField />
      <WorkersField />
      <DatesField />
      <TimeRangeField />
    </Form>
  );
};

VacationForm.propTypes = {
  onSave: PropTypes.func,
  onCancel: PropTypes.func
};

VacationForm.defaultProps = {
  onSave: f => f,
  onCancel: f => f
};
