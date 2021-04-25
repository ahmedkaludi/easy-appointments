import React from 'react';

import DoubleField from '../../../Form/DoubleField';
import MultiFieldWrap from '../../MultiFieldWrap';
import { StartDateField } from './StartDate';
import { EndDateField } from './EndDate';

const DateRangeField = ({ info, startName, endName }) => (
  <MultiFieldWrap label="Date range" className="ea-form-field" info={info}>
    <DoubleField>
      <StartDateField name={startName} />
      <EndDateField name={endName} startName={startName} />
    </DoubleField>
  </MultiFieldWrap>
);

export default DateRangeField;
