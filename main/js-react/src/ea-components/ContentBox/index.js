import React from 'react';
import PropTypes from 'prop-types';

import clsx from 'clsx';
import { Paper } from '@material-ui/core';

const ContentBox = ({ children, customClass }) => (
  <Paper square elevation={1} className={clsx('ea-content-box', customClass)}>
    {children}
  </Paper>
);

ContentBox.propTypes = {
  children: PropTypes.oneOfType([
    PropTypes.node,
    PropTypes.arrayOf(PropTypes.node)
  ]),
  customClass: PropTypes.string
};

ContentBox.defaultProps = {
  children: null,
  customClass: ''
};

export default ContentBox;
