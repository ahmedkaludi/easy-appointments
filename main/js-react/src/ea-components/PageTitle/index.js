import React, { Fragment } from 'react';
import PropTypes from 'prop-types';

import clsx from 'clsx';

import { connect } from 'react-redux';
// import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import logo from '../../assets/images/logo.png';

import { Paper, Box, Divider } from '@material-ui/core';

const PageTitle = ({
  pageTitleStyle,
  pageTitleBackground,
  pageTitleShadow,
  pageTitleBreadcrumb,
  pageTitleIconBox,
  pageTitleDescription,
  titleHeading,
  titleDescription
}) => {
  return (
    <Fragment>
      <Paper
        square
        elevation={pageTitleShadow ? 6 : 2}
        className={clsx('app-page-title', pageTitleStyle, pageTitleBackground)}>
        <div>
          <Box className="app-page-title--first">
            <Box className="easy-branding">
              <div className="easy-logo">
                <img alt="..." src={logo} />
              </div>
              <div className="page-title--heading easy">
                <h6>Easy Appointments</h6>
              </div>
            </Box>
            <Divider orientation="vertical" flexItem />
            <div className="app-page-title--description easy">
              {titleHeading}
            </div>
          </Box>
        </div>
      </Paper>
    </Fragment>
  );
};

PageTitle.propTypes = {
  titleHeading: PropTypes.string.isRequired,
  titleDescription: PropTypes.string
};

PageTitle.defaultProps = {
  titleDescription: ''
};

const mapStateToProps = state => ({
  pageTitleStyle: state.ThemeOptions.pageTitleStyle,
  pageTitleBackground: state.ThemeOptions.pageTitleBackground,
  pageTitleShadow: state.ThemeOptions.pageTitleShadow,
  pageTitleBreadcrumb: state.ThemeOptions.pageTitleBreadcrumb,
  pageTitleIconBox: state.ThemeOptions.pageTitleIconBox,
  pageTitleDescription: state.ThemeOptions.pageTitleDescription
});

export default connect(mapStateToProps)(PageTitle);
