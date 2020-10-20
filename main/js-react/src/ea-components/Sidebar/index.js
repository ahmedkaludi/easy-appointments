import React from 'react';
import PropTypes from 'prop-types';

import { Drawer, Box, Tooltip, IconButton, Divider } from '@material-ui/core';
import PerfectScrollbar from 'react-perfect-scrollbar';
import { X } from 'react-feather';

const Sidebar = ({ open, onClose, children, title }) => {
  return (
    <Drawer className="ea-sidebar" anchor="right" open={open}>
      <PerfectScrollbar className="ea-sidebar--wrapper">
        <div className="ea-sidebar--content">
          <Box className="ea-sidebar--header">
            <div className="ea-sidebar--header-title font-size-lg flex-grow-1">
              {title}
            </div>
            <Box className="ea-sidebar--header-close">
              <Tooltip arrow title="Close">
                <IconButton
                  color="primary"
                  className="rounded-sm"
                  onClick={onClose}>
                  <X className="font-size-xxl" />
                </IconButton>
              </Tooltip>
            </Box>
          </Box>
          <Divider />
          <Box className="ea-sidebar--body flex-grow-1">{children}</Box>
        </div>
      </PerfectScrollbar>
    </Drawer>
  );
};

Sidebar.propTypes = {
  open: PropTypes.bool,
  onClose: PropTypes.func,
  title: PropTypes.string,
  children: PropTypes.node
};

Sidebar.defaultProps = {
  open: false,
  onClose: f => f,
  title: '',
  children: null
};

export default Sidebar;
