import React from 'react';
import PropTypes from 'prop-types';

import { Drawer } from '@material-ui/core';

const Sidebar = ({ open, onClose }) => {
  return (
    <Drawer className="ea-sidebar" anchor="right" open={open} onClose={onClose}>
      <div style={{ width: '440px', height: '100%' }}>Sidebar</div>
    </Drawer>
  );
};

Sidebar.propTypes = {
  open: PropTypes.bool,
  onClose: PropTypes.func
};

Sidebar.defaultProps = {
  open: false,
  onClose: f => f
};

export default Sidebar;
