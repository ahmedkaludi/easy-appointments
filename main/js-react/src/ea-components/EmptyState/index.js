import React from 'react';

import { Box, Grid } from '@material-ui/core';

import vacation from '../../assets/images/empty-states/empty-vacation.svg';

export default function EmptyState() {
  return (
    <Grid
      item
      lg={6}
      md={9}
      className="ea-empty-state px-4 px-lg-0 mx-auto text-center text-black">
      <Box>
        <div className="easy-logo">
          <img alt="..." src={vacation} />
        </div>
        <h3 className="font-size-xxl line-height-sm font-weight-light d-block px-3 mb-3 text-black-50">
          There are no scheduled vacation days yet.
        </h3>
        <p className="mb-4">
          {`Use the 'Add vacation' button to add new vacation days.`}
        </p>
      </Box>
    </Grid>
  );
}
