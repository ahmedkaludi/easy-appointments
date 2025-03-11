import React, { useEffect, useRef } from 'react';
import PropTypes from 'prop-types';
import Sortable from 'sortablejs'; // Import Sortable

import { TableHeader } from './components/TableHeader';
import { TableRow } from './components/TableRow';
import { ServicesCommunicator } from 'communicators';

const ServiceDragTable = ({ data, config, rowCallback }) => {
  const tableBodyRef = useRef(null); // Create a ref for the tbody
  const updateSequence = async model => {
    try {
      await ServicesCommunicator.save(model);
    } catch (e) {
      console.log(e);
    }
  };
  // Initialize sortablejs once the table is rendered
  useEffect(() => {
    if (tableBodyRef.current) {
      const sortable = new Sortable(tableBodyRef.current, {
        handle: '.sortable-handle',
        animation: 150,
        onEnd(evt) {
          const draggedRow = evt.item;
          const draggedRowData = draggedRow.getAttribute('row-data');
          const parsedRowData = JSON.parse(draggedRowData);
          parsedRowData.sequence = evt.newIndex;
          updateSequence(parsedRowData);
        }
      });

      // Cleanup sortablejs when the component unmounts
      return () => {
        sortable.destroy();
      };
    }
  }, [data]);

  const headerConfig = Object.values(config);

  return (
    <div className="table-responsive">
      <table className="table table-hover text-nowrap mb-0">
        <TableHeader config={headerConfig} />
        <tbody ref={tableBodyRef} id="sortable-id">
          {data &&
            data.map(rowData => (
              <TableRow
                key={rowData.id}
                style={{ cursor: 'move' }}
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

ServiceDragTable.propTypes = {
  config: PropTypes.shape({
    headers: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any))
  }).isRequired,
  data: PropTypes.arrayOf(PropTypes.objectOf(PropTypes.any)),
  rowCallback: PropTypes.func
};

ServiceDragTable.defaultProps = {
  config: {},
  rowCallback: () => ({}),
  data: null
};

export default ServiceDragTable;
