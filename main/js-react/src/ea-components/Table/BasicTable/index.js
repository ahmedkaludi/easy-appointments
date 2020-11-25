import React from 'react';
import PropTypes from 'prop-types';

import { TableHeader } from './components/TableHeader';
import { TableRow } from './components/TableRow';

const BasicTable = ({ data, config }) => {
  const headerConfig = Object.values(config);
  return (
    <div className="table-responsive">
      <table className="table table-hover text-nowrap mb-0">
        <TableHeader config={headerConfig} />
        <tbody>
          {data &&
            data.map(vacation => <TableRow data={vacation} config={config} />)}
        </tbody>
      </table>
    </div>
  );
};

BasicTable.propTypes = {
  config: PropTypes.shape({
    headers: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
  }).isRequired,
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
};

BasicTable.defaultProps = {
  config: {},
  data: null
};

export default BasicTable;
