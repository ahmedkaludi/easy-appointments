import React from 'react';

import DoubleField from '../../../Form/DoubleField';
import MultiFieldWrap from '../../MultiFieldWrap';
import { StartDateField } from './StartDate';
import { EndDateField } from './EndDate';

const DateRangeField = ({ info }) => (
  <MultiFieldWrap label="Date range" className="ea-form-field" info={info}>
    <DoubleField>
      <StartDateField />
      <EndDateField />
    </DoubleField>
  </MultiFieldWrap>
);

export default DateRangeField;
