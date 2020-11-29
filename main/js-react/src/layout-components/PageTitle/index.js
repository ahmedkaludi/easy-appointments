import React, { Fragment, useState } from 'react';
import PropTypes from 'prop-types';
import clsx from 'clsx';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import {
  Grid,
  Input,
  InputLabel,
  InputAdornment,
  FormControlLabel,
  Hidden,
  IconButton,
  Paper,
  Box,
  Typography,
  Dialog,
  Checkbox,
  Tabs,
  Tab,
  LinearProgress,
  Card,
  CardContent,
  Button,
  Tooltip,
  FormControl
} from '@material-ui/core';

import MailOutlineTwoToneIcon from '@material-ui/icons/MailOutlineTwoTone';
import LockTwoToneIcon from '@material-ui/icons/LockTwoTone';

import FavoriteIcon from '@material-ui/icons/Favorite';
import EditIcon from '@material-ui/icons/Edit';

import DashboardTwoToneIcon from '@material-ui/icons/DashboardTwoTone';

import { connect } from 'react-redux';

import avatar1 from '../../assets/images/avatars/avatar1.jpg';

import avatar2 from '../../assets/images/avatars/avatar2.jpg';

import avatar6 from '../../assets/images/avatars/avatar6.jpg';
import avatar7 from '../../assets/images/avatars/avatar7.jpg';
import hero1 from '../../assets/images/hero-bg/hero-1.jpg';
import FileCopyIcon from '@material-ui/icons/FileCopyOutlined';
import SaveIcon from '@material-ui/icons/Save';
import PrintIcon from '@material-ui/icons/Print';
import ShareIcon from '@material-ui/icons/Share';

import Breadcrumbs from '@material-ui/core/Breadcrumbs';

import SpeedDial from '@material-ui/lab/SpeedDial';
import SpeedDialIcon from '@material-ui/lab/SpeedDialIcon';
import SpeedDialAction from '@material-ui/lab/SpeedDialAction';
import NavigateNextIcon from '@material-ui/icons/NavigateNext';

function TabPanel(props) {
  const { children, value, index, ...other } = props;

  return (
    <Typography
      component="div"
      role="tabpanel"
      hidden={value !== index}
      {...other}>
      {value === index && <Box p={4}>{children}</Box>}
    </Typography>
  );
}

TabPanel.propTypes = {
  children: PropTypes.node,
  index: PropTypes.any.isRequired,
  value: PropTypes.any.isRequired
};
const actions = [
  { icon: <FileCopyIcon />, name: 'Copy' },
  { icon: <SaveIcon />, name: 'Save' },
  { icon: <PrintIcon />, name: 'Print' },
  { icon: <ShareIcon />, name: 'Share' },
  { icon: <FavoriteIcon />, name: 'Like' }
];

function PageTitle(props) {
  const {
    pageTitleStyle,
    pageTitleBackground,
    pageTitleShadow,
    pageTitleBreadcrumb,
    pageTitleIconBox,
    pageTitleDescription
  } = props;

  const [modal1, setModal1] = useState(false);
  const toggle1 = () => setModal1(!modal1);

  const [value, setValue] = React.useState(0);

  const handleChange = (event, newValue) => {
    setValue(newValue);
  };

  const [checked1, setChecked1] = React.useState(true);

  const handleChange1 = event => {
    setChecked1(event.target.checked);
  };

  const [open, setOpen] = React.useState(false);

  const handleOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  return (
    <Fragment>
      <Paper
        square
        elevation={pageTitleShadow ? 6 : 2}
        className={clsx('app-page-title', pageTitleStyle, pageTitleBackground)}>
        <div>
          {pageTitleBreadcrumb && (
            <Breadcrumbs
              separator={<NavigateNextIcon fontSize="small" />}
              className="mb-4"
              maxItems={2}
              aria-label="breadcrumb">
              <Link color="inherit" to="#" onClick={e => e.preventDefault()}>
                Home
              </Link>
              <Link color="inherit" to="#" onClick={e => e.preventDefault()}>
                Dashboards
              </Link>
              <Link color="inherit" to="#" onClick={e => e.preventDefault()}>
                Examples
              </Link>
              <Link color="inherit" to="#" onClick={e => e.preventDefault()}>
                Pages
              </Link>
              <Typography color="textPrimary">{props.titleHeading}</Typography>
            </Breadcrumbs>
          )}

          <Box className="app-page-title--first">
            {pageTitleIconBox && (
              <Paper
                elevation={2}
                className="app-page-title--iconbox d-70 d-flex align-items-center bg-secondary justify-content-center">
                <DashboardTwoToneIcon />
              </Paper>
            )}
            <div className="app-page-title--heading">
              <h1>{props.titleHeading}</h1>
              {pageTitleDescription && (
                <div className="app-page-title--description">
                  {props.titleDescription}
                </div>
              )}
            </div>
          </Box>
        </div>

        {!open && (
          <div className="d-flex align-items-center">
            <Button onClick={toggle1} variant="contained" color="secondary">
              <span className="d-none d-xl-block">Open dialog</span>
              <span className="btn-wrapper--icon d-block d-xl-none">
                <FontAwesomeIcon icon={['far', 'object-group']} />
              </span>
            </Button>
            <Dialog scroll="body" maxWidth="lg" open={modal1} onClose={toggle1}>
              <Grid container spacing={0}>
                <Grid item xs={12} lg={5}>
                  <div className="hero-wrapper bg-composed-wrapper bg-plum-plate h-100">
                    <div className="flex-grow-1 w-100 d-flex align-items-center">
                      <div
                        className="bg-composed-wrapper--image "
                        style={{ backgroundImage: 'url(' + hero1 + ')' }}
                      />
                      <div className="bg-composed-wrapper--bg bg-second opacity-5" />
                      <div className="bg-composed-wrapper--content p-5">
                        <div className="d-flex align-items-center">
                          <span className="px-4 h-auto py-1 badge badge-second badge-pill">
                            New release
                          </span>
                          <Tooltip
                            arrow
                            placement="right"
                            title="More info placeholder!">
                            <span className="text-white-50 pl-3">
                              <FontAwesomeIcon
                                icon={['far', 'question-circle']}
                              />
                            </span>
                          </Tooltip>
                        </div>
                        <div className="text-white mt-3">
                          <h1 className="display-4 my-3 font-weight-bold">
                            Wonderful serenity has possession
                          </h1>
                          <p className="font-size-md mb-0 text-white-50">
                            A free hour, when our power of choice is
                            untrammelled and when nothing prevents.
                          </p>
                          <div className="divider border-2 my-5 border-light opacity-2 rounded w-25" />
                          <div>
                            <Button color="secondary" variant="contained">
                              <span className="btn-wrapper--label">
                                Browse gallery
                              </span>
                              <span className="btn-wrapper--icon">
                                <FontAwesomeIcon
                                  icon={['fas', 'arrow-right']}
                                />
                              </span>
                            </Button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div className="hero-footer pb-4">
                      <div className="d-flex justify-content-center">
                        <Tooltip arrow title="Facebook">
                          <IconButton
                            color="primary"
                            size="medium"
                            variant="outlined"
                            className="text-white">
                            <FontAwesomeIcon
                              icon={['fab', 'facebook']}
                              className="font-size-lg"
                            />
                          </IconButton>
                        </Tooltip>
                        <Tooltip arrow title="Twitter">
                          <IconButton
                            color="primary"
                            size="medium"
                            variant="outlined"
                            className="text-white">
                            <FontAwesomeIcon
                              icon={['fab', 'twitter']}
                              className="font-size-lg"
                            />
                          </IconButton>
                        </Tooltip>
                        <Tooltip arrow title="Google">
                          <IconButton
                            color="primary"
                            size="medium"
                            variant="outlined"
                            className="text-white">
                            <FontAwesomeIcon
                              icon={['fab', 'google']}
                              className="font-size-lg"
                            />
                          </IconButton>
                        </Tooltip>
                        <Tooltip arrow title="Instagram">
                          <IconButton
                            color="primary"
                            size="medium"
                            variant="outlined"
                            className="text-white">
                            <FontAwesomeIcon
                              icon={['fab', 'instagram']}
                              className="font-size-lg"
                            />
                          </IconButton>
                        </Tooltip>
                      </div>
                    </div>
                  </div>
                </Grid>
                <Grid item xs={12} lg={7}>
                  <div className="bg-white ">
                    <Tabs
                      value={value}
                      indicatorColor="primary"
                      textColor="primary"
                      variant="fullWidth"
                      onChange={handleChange}>
                      <Tab className="py-3" label="Timeline" />
                      <Tab className="py-3" label="Tasks" />
                      <Tab className="py-3" label="Reports" />
                    </Tabs>
                    <TabPanel value={value} index={0}>
                      <Grid container spacing={4}>
                        <Grid item md={6} lg={4}>
                          <Card className="card-box text-black-50 bg-secondary mb-5 p-3">
                            <div className="display-3 text-black font-weight-bold">
                              31
                            </div>
                            <div className="divider mt-2 mb-3 border-2 w-25 bg-first rounded border-first" />
                            <div className="font-weight-bold font-size-sm text-uppercase">
                              Implemented
                              <br />
                              bugfixes
                            </div>
                          </Card>
                        </Grid>
                        <Grid item md={6} lg={4}>
                          <Card className="card-box text-black-50 bg-secondary mb-5 p-3">
                            <div className="display-3 text-black font-weight-bold">
                              68
                            </div>
                            <div className="divider mt-2 mb-3 border-2 w-25 bg-success rounded border-success" />
                            <div className="font-weight-bold font-size-sm text-uppercase">
                              Unresolved
                              <br />
                              tickets
                            </div>
                          </Card>
                        </Grid>
                        <Grid item md={6} lg={4}>
                          <Card className="card-box text-black-50 bg-secondary mb-5 p-3">
                            <div className="display-3 text-black font-weight-bold">
                              57
                            </div>
                            <div className="divider mt-2 mb-3 border-2 w-25 bg-warning rounded border-warning" />
                            <div className="font-weight-bold font-size-sm text-uppercase">
                              Support
                              <br />
                              requests
                            </div>
                          </Card>
                        </Grid>
                      </Grid>
                      <Card className="card-box bg-secondary mb-5">
                        <CardContent className="p-3">
                          <div className="text-left">
                            <div className="mt-1">
                              <FontAwesomeIcon
                                icon={['fas', 'lemon']}
                                className="font-size-xxl text-danger"
                              />
                            </div>
                            <div className="mt-3 line-height-sm">
                              <b className="font-size-lg text-black">3,568</b>
                              <span className="text-black-50 pl-1">clicks</span>
                            </div>
                          </div>
                          <div className="mt-3">
                            <LinearProgress color="primary" value={85} />
                            <div className="align-box-row progress-bar--label mt-2 text-muted">
                              <div>Target</div>
                              <div className="ml-auto">100%</div>
                            </div>
                          </div>
                        </CardContent>
                      </Card>
                      <div className="text-center">
                        <Button variant="outlined" color="primary">
                          Create new report
                        </Button>
                      </div>
                    </TabPanel>
                    <TabPanel value={value} index={1}>
                      <Card className="m-0 bg-secondary border-0">
                        <div className="card-header d-block p-3 mx-2 mb-0 mt-2 rounded border-0">
                          <div className="text-muted text-center mb-3">
                            <small>Sign in with</small>
                          </div>
                          <div className="text-center">
                            <Button
                              variant="outlined"
                              className="mr-2 text-facebook">
                              <span className="btn-wrapper--icon">
                                <FontAwesomeIcon icon={['fab', 'facebook']} />
                              </span>
                              <span className="btn-wrapper--label">
                                Facebook
                              </span>
                            </Button>
                            <Button
                              variant="outlined"
                              className="ml-2 text-twitter">
                              <span className="btn-wrapper--icon">
                                <FontAwesomeIcon icon={['fab', 'twitter']} />
                              </span>
                              <span className="btn-wrapper--label">
                                Twitter
                              </span>
                            </Button>
                          </div>
                        </div>
                        <CardContent className="p-3">
                          <div className="text-center text-black-50 mb-3">
                            <small>Or sign in with credentials</small>
                          </div>
                          <form className="px-5">
                            <div className="mb-3">
                              <FormControl className="w-100">
                                <InputLabel htmlFor="input-with-icon-adornment">
                                  Email address
                                </InputLabel>
                                <Input
                                  fullWidth
                                  id="input-with-icon-adornment"
                                  startAdornment={
                                    <InputAdornment position="start">
                                      <MailOutlineTwoToneIcon />
                                    </InputAdornment>
                                  }
                                />
                              </FormControl>
                            </div>
                            <div className="mb-3">
                              <FormControl className="w-100">
                                <InputLabel htmlFor="standard-adornment-password">
                                  Password
                                </InputLabel>
                                <Input
                                  id="standard-adornment-password"
                                  fullWidth
                                  type="password"
                                  startAdornment={
                                    <InputAdornment position="start">
                                      <LockTwoToneIcon />
                                    </InputAdornment>
                                  }
                                />
                              </FormControl>
                            </div>
                            <div className="w-100">
                              <FormControlLabel
                                control={
                                  <Checkbox
                                    checked={checked1}
                                    onChange={handleChange1}
                                    value="checked1"
                                    color="primary"
                                  />
                                }
                                label="Remember me"
                              />
                            </div>
                            <div className="text-center">
                              <Button
                                color="primary"
                                variant="contained"
                                size="large"
                                className="my-2">
                                Sign in
                              </Button>
                            </div>
                          </form>
                        </CardContent>
                      </Card>
                    </TabPanel>
                    <TabPanel value={value} index={2}>
                      <Grid container spacing={4}>
                        <Grid item xs={12} md={6}>
                          <Card className="card-box bg-secondary mb-5">
                            <div className="card-indicator bg-first" />
                            <CardContent className="px-4 py-3">
                              <div className="pb-3 d-flex justify-content-between">
                                <a href="#/" onClick={e => e.preventDefault()}>
                                  Presentation site design
                                </a>
                              </div>
                              <div className="d-flex align-items-center justify-content-start">
                                <div className="badge badge-primary px-3">
                                  On Hold
                                </div>
                                <div className="font-size-sm text-danger px-2">
                                  <FontAwesomeIcon
                                    icon={['far', 'clock']}
                                    className="mr-1"
                                  />
                                  14:22
                                </div>
                              </div>
                            </CardContent>
                          </Card>
                        </Grid>
                        <Grid item xs={12} md={6}>
                          <Card className="card-box bg-secondary mb-5">
                            <div className="card-indicator bg-success" />
                            <CardContent className="px-4 py-3">
                              <div className="pb-3 d-flex justify-content-between">
                                <a href="#/" onClick={e => e.preventDefault()}>
                                  Create UI mockups
                                </a>
                              </div>
                              <div className="d-flex align-items-center justify-content-start">
                                <div className="px-3 badge badge-success">
                                  Fixed
                                </div>
                                <div className="font-size-sm text-dark px-2">
                                  <FontAwesomeIcon
                                    icon={['far', 'clock']}
                                    className="mr-1"
                                  />
                                  09:41
                                </div>
                              </div>
                            </CardContent>
                          </Card>
                        </Grid>
                      </Grid>
                      <div className="timeline-list">
                        <div className="timeline-item timeline-item-icon">
                          <div className="timeline-item--content">
                            <div className="timeline-item--icon-wrapper bg-danger text-white">
                              <FontAwesomeIcon icon={['far', 'gem']} />
                            </div>
                            <h4 className="timeline-item--label mb-2 font-weight-bold">
                              1998
                            </h4>
                            <p>
                              Bill Clinton's presidential scandal makes it
                              online.
                            </p>
                          </div>
                        </div>
                        <div className="timeline-item">
                          <div className="timeline-item--content">
                            <div className="timeline-item--icon" />
                            <h4 className="timeline-item--label mb-2 font-weight-bold">
                              Java exam day
                            </h4>
                            <p>
                              Bill Clinton's presidential scandal makes it
                              online.
                            </p>
                            <div className="avatar-wrapper-overlap mt-2 mb-1">
                              <div className="avatar-icon-wrapper avatar-icon-sm">
                                <div className="avatar-icon">
                                  <img alt="..." src={avatar1} />
                                </div>
                              </div>
                              <div className="avatar-icon-wrapper avatar-icon-sm">
                                <div className="avatar-icon">
                                  <img alt="..." src={avatar7} />
                                </div>
                              </div>
                              <div className="avatar-icon-wrapper avatar-icon-sm">
                                <div className="avatar-icon">
                                  <img alt="..." src={avatar1} />
                                </div>
                              </div>
                              <div className="avatar-icon-wrapper avatar-icon-sm">
                                <div className="avatar-icon">
                                  <img alt="..." src={avatar2} />
                                </div>
                              </div>
                              <div className="avatar-icon-wrapper avatar-icon-sm">
                                <div className="avatar-icon">
                                  <img alt="..." src={avatar6} />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div className="timeline-item">
                          <div className="timeline-item--content">
                            <div className="timeline-item--icon" />
                            <h4 className="timeline-item--label mb-2 font-weight-bold">
                              Business investor meeting
                              <span className="text-info ml-2 badge badge-neutral-info">
                                New
                              </span>
                            </h4>
                            <p>
                              Mosaic, the first graphical browser, is introduced
                              to the average consumer.
                            </p>
                            <div className="mt-2">
                              <Button
                                size="small"
                                variant="contained"
                                color="primary">
                                Submit Report
                              </Button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </TabPanel>
                  </div>
                </Grid>
              </Grid>
            </Dialog>
          </div>
        )}
        <Hidden smDown>
          <div className="speedial-wrapper">
            <SpeedDial
              ariaLabel="SpeedDial menu"
              icon={<SpeedDialIcon openIcon={<EditIcon />} />}
              onClose={handleClose}
              onOpen={handleOpen}
              direction="left"
              open={open}>
              {actions.map(action => (
                <SpeedDialAction
                  key={action.name}
                  icon={action.icon}
                  tooltipTitle={action.name}
                  onClick={handleClose}
                />
              ))}
            </SpeedDial>
          </div>
        </Hidden>
      </Paper>
    </Fragment>
  );
}
const mapStateToProps = state => ({
  pageTitleStyle: state.ThemeOptions.pageTitleStyle,
  pageTitleBackground: state.ThemeOptions.pageTitleBackground,
  pageTitleShadow: state.ThemeOptions.pageTitleShadow,
  pageTitleBreadcrumb: state.ThemeOptions.pageTitleBreadcrumb,
  pageTitleIconBox: state.ThemeOptions.pageTitleIconBox,
  pageTitleDescription: state.ThemeOptions.pageTitleDescription
});

export default connect(mapStateToProps)(PageTitle);
