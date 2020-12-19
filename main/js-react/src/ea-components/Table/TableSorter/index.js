import React, { useState } from 'react';
import PropTypes from 'prop-types';

import Select from '../../FormFields/Select';

const DEFAULT_PARAMS = { column: '', order: '' };

const ORDER_OPTIONS = [
  { value: 'ASC', label: 'ASC' },
  { value: 'DESC', label: 'DESC' }
];

const TableSorter = ({ columns, sortingFunc, onSortingDone }) => {
  const [params, setParams] = useState(DEFAULT_PARAMS);

  const onChange = async (column, order) => {
    const sorted = await sortingFunc(column, order);

    if (sorted) {
      onSortingDone();
    }
  };

  const onChangeOrder = async e => {
    console.log('======', e.target.value);
    await onChange(params.column, e.target.value);
    setParams({ ...params, order: e.target.value });
  };

  return (
    <div>
      <Select
        value={params.order}
        onChange={onChangeOrder}
        label="Order by"
        options={ORDER_OPTIONS}
      />
    </div>
  );
};

TableSorter.propTypes = {
  columns: PropTypes.arrayOf(PropTypes.any),
  sortingFunc: PropTypes.func.isRequired,
  onSortingDone: PropTypes.func
};

TableSorter.defaultProps = {
  columns: [],
  onSortingDone: f => f
};

export default TableSorter;
