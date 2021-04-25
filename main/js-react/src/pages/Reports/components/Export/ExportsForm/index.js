import React, { useState } from 'react';

import { Form, DateRangeField } from '../../../../../ea-components';
import { ColumnsField } from './fields/ColumnsField';

export const ExportsForm = () => {
  const [report, setReport] = useState({});

  const onCancel = () => setReport({});

  const onSave = () => setReport({});

  return (
    <Form
      model={report}
      onCancel={onCancel}
      onSave={onSave}
      customClass="export-form">
      <span className="mb-4 d-block ea-text-gray-1">
        Choose custom columns for your report. File is exported as CSV, and can
        be later imported to MS Excel, OpenOffice etc.
      </span>
      <ColumnsField />
      <DateRangeField info="Some info" />
    </Form>
  );
};
