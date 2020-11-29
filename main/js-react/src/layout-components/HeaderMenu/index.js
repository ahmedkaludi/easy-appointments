import React, { Fragment } from 'react';

import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import {
  Grid,
  Box,
  Typography,
  Popover,
  Menu,
  Button,
  List,
  ListItem,
  Divider
} from '@material-ui/core';

import { Settings, Briefcase, Users, Layers } from 'react-feather';

const HeaderMenu = () => {
  const [anchorEl, setAnchorEl] = React.useState(null);

  const handleClick = event => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const open = Boolean(anchorEl);
  const id = open ? 'mega-menu-popover' : undefined;

  const [anchorElMenu, setAnchorElMenu] = React.useState(null);

  const handleClickMenu = event => {
    setAnchorElMenu(event.currentTarget);
  };

  const handleCloseMenu = () => {
    setAnchorElMenu(null);
  };

  return (
    <Fragment>
      <div className="app-header-menu">
        <Button
          size="medium"
          color="inherit"
          onClick={handleClick}
          className="btn-inverse font-size-xs mx-2">
          Mega menu
        </Button>
        <Popover
          id={id}
          open={open}
          anchorEl={anchorEl}
          onClose={handleClose}
          anchorOrigin={{
            vertical: 'bottom',
            horizontal: 'left'
          }}
          transformOrigin={{
            vertical: 'top',
            horizontal: 'left'
          }}>
          <div className="popover-custom-xxl p-2">
            <Grid container spacing={0}>
              <Grid item xs={4}>
                <div className="divider-v divider-v-lg" />
                <List className="nav-neutral-first flex-column p-2">
                  <Typography
                    color="primary"
                    component="div"
                    className="pb-2 text-capitalize font-size-lg font-weight-bold">
                    <span>Dashboards</span>
                  </Typography>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/DashboardAnalytics">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Analytics</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/DashboardReports">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Reports</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/DashboardRealEstate">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Real Estate</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/DashboardServerStatus">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Server Stats</span>
                    <div className="ml-auto badge badge-pill badge-success">
                      8
                    </div>
                  </ListItem>
                  <Divider className="my-3" />
                  <ListItem className="px-0 pb-0">
                    <Button
                      to="/DashboardDefault"
                      color="primary"
                      size="small"
                      variant="outlined"
                      className="w-100">
                      <span className="btn-wrapper--label">Learn more</span>
                    </Button>
                  </ListItem>
                </List>
              </Grid>
              <Grid item xs={4}>
                <div className="divider-v divider-v-lg" />
                <List className="nav-neutral-success flex-column p-2">
                  <Typography
                    color="primary"
                    className="pb-2 text-capitalize font-size-lg font-weight-bold">
                    <span>Applications</span>
                  </Typography>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/ApplicationsCalendar">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Calendar</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/ApplicationsChat">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Chat</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/ApplicationsContacts">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Contacts</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/ApplicationsFileManager">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>File Manager</span>
                  </ListItem>
                  <Divider className="my-3" />
                  <ListItem className="px-0 pb-0">
                    <Button
                      to="/ApplicationsMail"
                      size="small"
                      color="primary"
                      variant="outlined"
                      className="w-100">
                      <span className="btn-wrapper--label">Learn more</span>
                    </Button>
                  </ListItem>
                </List>
              </Grid>
              <Grid item xs={4}>
                <List className="nav-neutral-danger flex-column p-2">
                  <Typography
                    color="primary"
                    className="pb-2 text-capitalize font-size-lg font-weight-bold">
                    <span>Components</span>
                  </Typography>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/Cards4">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Cards examples</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/RegularTables2">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Tables</span>
                    <div className="ml-auto badge badge-pill badge-neutral-danger text-danger">
                      New
                    </div>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/FormsWizard">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Form wizards</span>
                  </ListItem>
                  <ListItem
                    button
                    className="rounded-sm"
                    component={Link}
                    to="/PricingTables">
                    <div className="nav-link-icon w-auto mr-2">
                      <FontAwesomeIcon
                        icon={['fas', 'chevron-right']}
                        className="font-size-xs opacity-3"
                      />
                    </div>
                    <span>Pricing tables</span>
                  </ListItem>
                  <Divider className="my-3" />
                  <ListItem className="px-0 pb-0">
                    <Button
                      to="/NavigationMenus"
                      color="primary"
                      size="small"
                      variant="outlined"
                      className="w-100">
                      <span className="btn-wrapper--label">Learn more</span>
                    </Button>
                  </ListItem>
                </List>
              </Grid>
            </Grid>
          </div>
        </Popover>

        <Button
          onClick={handleClickMenu}
          color="inherit"
          size="medium"
          className="btn-inverse font-size-xs mr-3">
          Dashboards
        </Button>
        <Menu
          anchorEl={anchorElMenu}
          keepMounted
          open={Boolean(anchorElMenu)}
          onClose={handleCloseMenu}
          classes={{ list: 'p-0' }}
          getContentAnchorEl={null}
          anchorOrigin={{
            vertical: 'bottom',
            horizontal: 'center'
          }}
          transformOrigin={{
            vertical: 'top',
            horizontal: 'center'
          }}>
          <Box className="overflow-hidden border-0 bg-deep-sky p-3 dropdown-mega-menu-md">
            <div className="text-center">
              <div className="font-weight-bold font-size-lg mb-1 text-white">
                Dashboards
              </div>
              <p className="text-white-50 mb-3">
                There are currently <b className="text-white">12</b> dashboard
                layouts available!
              </p>
            </div>
            <div className="d-flex flex-wrap">
              <div className="w-50 p-2">
                <Link
                  to="/DashboardProjects"
                  className="btn card card-box text-left d-flex justify-content-center px-3 py-2 w-100 border-0">
                  <div>
                    <Briefcase className="h1 d-block my-2 text-success" />
                    <div className="font-weight-bold font-size-lg text-black">
                      Projects
                    </div>
                    <div className="font-size-sm mb-1 text-black-50">
                      Dashboard 9
                    </div>
                  </div>
                </Link>
              </div>
              <div className="w-50 p-2">
                <Link
                  to="/DashboardHelpdesk"
                  className="btn card card-box text-left d-flex justify-content-center px-3 py-2 w-100 border-0">
                  <div>
                    <Users className="h1 d-block my-2 text-danger" />
                    <div className="font-weight-bold font-size-lg text-black">
                      Helpdesk
                    </div>
                    <div className="font-size-sm mb-1 text-black-50">
                      Dashboard 4
                    </div>
                  </div>
                </Link>
              </div>
              <div className="w-50 p-2">
                <Link
                  to="/DashboardCrmManager"
                  className="btn card card-box text-left d-flex justify-content-center px-3 py-2 w-100 border-0">
                  <div>
                    <Settings className="h1 d-block my-2 text-warning" />
                    <div className="font-weight-bold font-size-lg text-black">
                      CRM UI
                    </div>
                    <div className="font-size-sm mb-1 text-black-50">
                      Dashboard 6
                    </div>
                  </div>
                </Link>
              </div>
              <div className="w-50 p-2">
                <Link
                  to="/DashboardCustomers"
                  className="btn card card-box text-left d-flex justify-content-center px-3 py-2 w-100 border-0">
                  <div>
                    <Layers className="h1 d-block my-2 text-white" />
                    <div className="font-weight-bold font-size-lg text-black">
                      Customers
                    </div>
                    <div className="font-size-sm mb-1 text-black-50">
                      Dashboard 12
                    </div>
                  </div>
                </Link>
              </div>
            </div>
          </Box>
        </Menu>
      </div>
    </Fragment>
  );
};

export default HeaderMenu;
