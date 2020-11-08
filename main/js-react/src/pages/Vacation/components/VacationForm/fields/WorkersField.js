import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import { DataService } from '../../../../../services';
import { Field, Autocomplete } from '../../../../../ea-components';

const Workers = ({ value, updateValue, name }) => {
  const [options, setOptions] = useState([]);
  const [workers, setWorkers] = useState([]);

  const loadWorkers = () => {
    let workersData = DataService.get('Workers');
    setWorkers(workersData || []);

    workersData = workersData
      ? workersData.map(worker => ({ value: worker.id, label: worker.name }))
      : [];

    setOptions(workersData);
  };

  useEffect(() => {
    loadWorkers();
  }, []);

  const onChange = (e, newVal) => {
    const newValIds = newVal.map(val => val.value);
    const selected = workers.filter(wrk => newValIds.includes(wrk.id));
    updateValue(name, selected);
  };

  const selected = value
    ? value.map(worker => ({ value: worker.id, label: worker.name }))
    : [];

  return (
    <Autocomplete
      label="Workers"
      placeholder="Worker..."
      value={selected}
      onChange={onChange}
      options={options}
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
