import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import { DataService } from '../../../../../services';
import { Field, Select } from '../../../../../ea-components';

const Worker = ({ value, updateValue, name }) => {
  const [workers, setWorkers] = useState([]);

  const loadWorkers = () => {
    let workersData = DataService.get('Workers');

    workersData = workersData
      ? workersData.map(worker => ({ value: worker.id, label: worker.name }))
      : [];

    setWorkers(workersData);
  };

  useEffect(() => {
    loadWorkers();
  }, []);

  const onChange = val => updateValue(name, val);

  return (
    <Select
      label="Worker"
      value={value || ''}
      onChange={e => onChange(e.target.value)}
      options={workers}
    />
  );
};

Worker.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateValue: PropTypes.func,
  name: PropTypes.string
};

Worker.defaultProps = {
  value: null,
  updateValue: f => f,
  name: ''
};

export const WorkerField = () => (
  <Field name="worker" component={props => <Worker {...props} />} />
);
