import React, { Fragment } from 'react';
import PropTypes from 'prop-types';

import clsx from 'clsx';

import { connect } from 'react-redux';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
// import logo from '../../assets/images/logo.png';
import logo from '../../assets/images/logo.svg';

import { Paper, Box, Divider, Button } from '@material-ui/core';

const PageTitle = ({
  pageTitleStyle,
  pageTitleBackground,
  pageTitleShadow,
  pageTitleBreadcrumb,
  pageTitleIconBox,
  titleHeading,
  action
}) => {
  return (
    <Fragment>
      <Paper
        square
        elevation={pageTitleShadow ? 6 : 2}
        className={clsx(
          'app-page-title easy',
          pageTitleStyle,
          pageTitleBackground
        )}>
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

        {action ? (
          <div className="d-flex align-items-center">
            <Button
              onClick={action.callback}
              variant="contained"
              color="primary">
              <span className="btn-wrapper--icon">
                <FontAwesomeIcon icon={['far', `${action.icon}`]} />
              </span>
              <span className="btn-wrapper--label">{action.text}</span>
            </Button>
          </div>
        ) : null}
      </Paper>
    </Fragment>
  );
};

PageTitle.propTypes = {
  titleHeading: PropTypes.string.isRequired,
  action: PropTypes.objectOf(PropTypes.any)
};

PageTitle.defaultProps = {
  action: null
};

const mapStateToProps = state => ({
  pageTitleStyle: state.ThemeOptions.pageTitleStyle,
  pageTitleBackground: state.ThemeOptions.pageTitleBackground,
  pageTitleShadow: state.ThemeOptions.pageTitleShadow,
  pageTitleBreadcrumb: state.ThemeOptions.pageTitleBreadcrumb,
  pageTitleIconBox: state.ThemeOptions.pageTitleIconBox
});

export default connect(mapStateToProps)(PageTitle);
