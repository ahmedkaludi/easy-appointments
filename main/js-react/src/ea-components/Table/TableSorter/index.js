import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

import Select from '../../FormFields/Select';

const DEFAULT_PARAMS = { column: null, order: null };

const ORDER_OPTIONS = [
  { value: 'ASC', label: 'ASC' },
  { value: 'DESC', label: 'DESC' }
];

const TableSorter = ({ columns, sortingFunc, onSortingDone, hint }) => {
  const [params, setParams] = useState(DEFAULT_PARAMS);

  const onChange = async newState => {
    const { column, order } = newState;

    if (!column || !order) {
      return;
    }

    const sorted = await sortingFunc(column, order);

    if (sorted) {
      onSortingDone();
    }
  };

  useEffect(() => {
    onChange(params);
  }, [params]);

  const onChangeOrder = e => setParams({ ...params, order: e.target.value });

  const onChangeColumn = e => setParams({ ...params, column: e.target.value });

  return (
    <div className="p-3 d-flex justify-content-between align-items-end">
      <div className="d-flex justify-content-start align-items-center">
        <Select
          value={params.column || ''}
          onChange={onChangeColumn}
          label="Sort by"
          options={columns}
          customClass="standalone ea-min-width-130 mr-1"
        />
        <Select
          value={params.order || ''}
          onChange={onChangeOrder}
          label="Order by"
          options={ORDER_OPTIONS}
          customClass="standalone ea-min-width-100"
        />
      </div>
      {hint && <span className="ea-text font-size-xs pl-1">{hint}</span>}
    </div>
  );
};

TableSorter.propTypes = {
  columns: PropTypes.arrayOf(PropTypes.any),
  sortingFunc: PropTypes.func.isRequired,
  onSortingDone: PropTypes.func,
  hint: PropTypes.string
};

TableSorter.defaultProps = {
  columns: [],
  onSortingDone: f => f,
  hint: null
};

export default TableSorter;
