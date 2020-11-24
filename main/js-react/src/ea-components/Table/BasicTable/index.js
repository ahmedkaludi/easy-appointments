import React from 'react';
import PropTypes from 'prop-types';

import { TableHeader } from './components/TableHeader';

const BasicTable = ({ data, headers }) => {
  return (
    <div className="table-responsive">
      <table className="table table-hover text-nowrap mb-0">
        <TableHeader config={headers} />
        {data && data.map(vacation => <div>{vacation.title}</div>)}
      </table>
    </div>
  );
};

BasicTable.propTypes = {
  headers: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
};

BasicTable.defaultProps = {
  headers: null,
  data: null
};

export default BasicTable;
