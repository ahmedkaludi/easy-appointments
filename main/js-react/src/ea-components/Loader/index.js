import React from 'react';
import PropTypes from 'prop-types';

import { Box } from '@material-ui/core';

import loaderImg from '../../assets/images/loader.svg';
import { getSettings } from '../../services';

const imageBase = getSettings('image_base', '');

export default function Loader({ size, text }) {
  return (
    <Box className="ea-loader m-auto text-center">
      <div className="d-flex flex-column m-auto">
        <img
          className="m-auto d-block img-fluid"
          src={imageBase + loaderImg}
          width={size}
        />
        {text && <p className="loading-text mt-3">{text}</p>}
      </div>
    </Box>
  );
}

Loader.propTypes = {
  size: PropTypes.number,
  text: PropTypes.string
};

Loader.defaultProps = {
  size: 50,
  text: null
};
