import React from 'react';
import PropTypes from 'prop-types';
import { Box, Grid } from '@material-ui/core';

import { getSettings } from '../../services';
import vacationImg from '../../assets/images/empty-states/empty-vacation.svg';
import locationsImg from '../../assets/images/empty-states/empty-locations.svg';
import employeesImg from '../../assets/images/empty-states/empty-employees.svg';
import servicesImg from '../../assets/images/empty-states/empty-services.svg';
import connectionsImg from '../../assets/images/empty-states/empty-connections.svg';
import toolsImg from '../../assets/images/empty-states/empty-tools.svg';

const imageBase = getSettings('image_base', '');

const imgSources = {
  vacation: imageBase + vacationImg,
  location: imageBase + locationsImg,
  service: imageBase + servicesImg,
  worker: imageBase + employeesImg,
  connection: imageBase + connectionsImg,
  tools: imageBase + toolsImg
};

export default function EmptyState({ type, message, hint }) {
  return (
    <Grid
      item
      lg={6}
      md={9}
      sm={12}
      className="ea-empty-state px-4 px-lg-0 mx-auto text-center text-black">
      <Box>
        <div className="ea-empty-imgwrap">
          <img
            alt="..."
            className="w-50 mx-auto d-block my-5 img-fluid"
            src={imgSources[type]}
          />
        </div>
        <h3 className="font-size-xxl line-height-sm font-weight-light d-block px-3 mb-3 text-black-50">
          {message}
        </h3>
        {hint ? <p className="mb-4">{hint}</p> : null}
      </Box>
    </Grid>
  );
}

EmptyState.propTypes = {
  type: PropTypes.string.isRequired,
  message: PropTypes.string.isRequired,
  hint: PropTypes.string
};

EmptyState.defaultProps = {
  hint: ''
};
