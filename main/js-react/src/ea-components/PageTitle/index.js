import React, { Fragment } from 'react';
import PropTypes from 'prop-types';

import clsx from 'clsx';

import { connect } from 'react-redux';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import logo from '../../assets/images/logo.svg';

import { Paper, Box, Divider, Button } from '@material-ui/core';
import { getSettings } from '../../services';

const imageBase = getSettings('image_base', '');

const PageTitle = ({
  pageTitleStyle,
  pageTitleBackground,
  pageTitleShadow,
  pageTitleBreadcrumb,
  pageTitleIconBox,
  titleHeading,
  action
}) => (
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
              <img alt="..." src={imageBase + logo} />
            </div>
            <div className="page-title--heading easy">
              <h6>Easy Appointments</h6>
            </div>
          </Box>
          <Divider orientation="vertical" flexItem />
          <div className="app-page-title--description easy">{titleHeading}</div>
        </Box>
      </div>

      {action ? (
        // case it is array
        Array.isArray(action) ? (
          <div className="d-flex align-items-center">
            {action.map(a => (
              <Button
                onClick={a.callback}
                variant="contained"
                color="primary"
                style={{ marginLeft: '10px' }}>
                <span className="btn-wrapper--icon">
                  <FontAwesomeIcon icon={['fas', `${a.icon}`]} />
                </span>
                <span className="btn-wrapper--label">{a.text}</span>
              </Button>
            ))}
          </div>
        ) : (
          // Case then that is a single action button
          <div className="d-flex align-items-center">
            <Button
              onClick={action.callback}
              variant="contained"
              color="primary">
              <span className="btn-wrapper--icon">
                <FontAwesomeIcon icon={['fas', `${action.icon}`]} />
              </span>
              <span className="btn-wrapper--label">{action.text}</span>
            </Button>
          </div>
        )
      ) : null}
    </Paper>
  </Fragment>
);

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
