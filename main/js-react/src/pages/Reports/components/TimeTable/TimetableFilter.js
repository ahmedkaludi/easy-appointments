import React from 'react';

import { Select } from '../../../../ea-components';

export const TimetableFilter = () => {
  return (
    <div className="pr-3">
      <Select
        value={''}
        onChange={f => f}
        label="Location"
        options={[]}
        customClass="standalone ea-min-width-130 mr-1"
      />
      <Select
        value={''}
        onChange={f => f}
        label="Service"
        options={[]}
        customClass="standalone ea-min-width-100"
      />
      <Select
        value={''}
        onChange={f => f}
        label="Worker"
        options={[]}
        customClass="standalone ea-min-width-100"
      />
    </div>
  );
};
