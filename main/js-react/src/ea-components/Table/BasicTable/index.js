import React from 'react';
import PropTypes from 'prop-types';

import { TableHeader } from './components/TableHeader';
import { TableRow } from './components/TableRow';

const BasicTable = ({ data, config, rowCallback }) => {
  const headerConfig = Object.values(config);

  return (
    <div className="table-responsive">
      <table className="table table-hover text-nowrap mb-0">
        <TableHeader config={headerConfig} />
        <tbody>
          {data &&
            data.map(rowData => (
              <TableRow
                data={rowData}
                config={config}
                {...rowCallback(rowData)}
              />
            ))}
        </tbody>
      </table>
    </div>
  );
};

BasicTable.propTypes = {
  config: PropTypes.shape({
    headers: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
  }).isRequired,
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  rowCallback: PropTypes.func
};

BasicTable.defaultProps = {
  config: {},
  rowCallback: () => ({}),
  data: null
};

export default BasicTable;
