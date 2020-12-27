import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import { DataService } from '../../../../../services';
import { __ } from '../../../../../services/Localization';
import { Field, Autocomplete } from '../../../../../ea-components';

const Workers = ({ value, updateFieldValue, error }) => {
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
    updateFieldValue(selected);
  };

  const selected = value
    ? value.map(worker => ({ value: worker.id, label: worker.name }))
    : [];

  return (
    <Autocomplete
      label={__('Workers *', 'easy-appointments')}
      placeholder={__('Worker...', 'easy-appointments')}
      value={selected}
      onChange={onChange}
      options={options}
      error={error}
    />
  );
};

Workers.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Workers.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const WorkersField = () => (
  <Field required name="workers" component={props => <Workers {...props} />} />
);
