import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import { DataService } from '../../../../../services';
import { Field, Autocomplete } from '../../../../../ea-components';

const Workers = ({ value, updateValue, name }) => {
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

  const onChange = (e, val) => updateValue(name, val);
  console.log('Workers =========> ', workers);

  return (
    <Autocomplete
      label="Workers"
      placeholder="Worker..."
      value={value || []}
      onChange={e => onChange(e.target.value)}
      options={workers}
    />
  );
};

Workers.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateValue: PropTypes.func,
  name: PropTypes.string
};

Workers.defaultProps = {
  value: null,
  updateValue: f => f,
  name: ''
};

export const WorkersField = () => (
  <Field name="workers" component={props => <Workers {...props} />} />
);
