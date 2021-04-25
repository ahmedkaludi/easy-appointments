import React from 'react';

import { Form, DateRangeField } from '../../../../../ea-components';
import { ColumnsField } from './fields/ColumnsField';
import { ExportButton } from './fields/ExportButton';

export const ExportsForm = () => (
  <Form customClass="export-form" hideFooter>
    <span className="mb-4 d-block ea-text-gray-1">
      Choose custom columns for your report. File is exported as CSV, and can be
      later imported to MS Excel, OpenOffice etc.
    </span>
    <ColumnsField />
    <DateRangeField
      info="Select daterange for your report"
      startName="ea-export-from"
      endName="ea-export-to"
    />
    <ExportButton />
  </Form>
);
