import React, { Fragment } from 'react';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import PerfectScrollbar from 'react-perfect-scrollbar';
import {
  Grid,
  Hidden,
  Drawer,
  IconButton,
  Box,
  Checkbox,
  Badge,
  Button,
  List,
  ListItem,
  Tooltip,
  Divider
} from '@material-ui/core';

import avatar1 from '../../assets/images/avatars/avatar1.jpg';
import avatar2 from '../../assets/images/avatars/avatar2.jpg';
import avatar6 from '../../assets/images/avatars/avatar6.jpg';
import avatar7 from '../../assets/images/avatars/avatar7.jpg';

import MenuOpenRoundedIcon from '@material-ui/icons/MenuOpenRounded';

import ListIcon from '@material-ui/icons/List';

import Circle from 'react-circle';
import CountUp from 'react-countup';
import Chart from 'react-apexcharts';

function HeaderDrawer() {
  const chart36Options = {
    chart: {
      toolbar: {
        show: false
      },
      sparkline: {
        enabled: true
      }
    },

    stroke: {
      color: '#1bc943',
      curve: 'smooth',
      width: 4
    },
    colors: ['#1bc943'],
    fill: {
      color: '1bc943',
      gradient: {
        shade: 'light',
        type: 'vertical',
        shadeIntensity: 0.7,
        inverseColors: true,
        opacityFrom: 1,
        opacityTo: 0,
        stops: [0, 100]
      }
    },
    xaxis: {
      crosshairs: {
        width: 1
      }
    },
    yaxis: {
      min: 0
    }
  };
  const chart36Data = [
    {
      name: 'Monthly Analytics',
      data: [47, 38, 56, 24, 45, 54, 38, 56, 24, 65]
    }
  ];

  const [open, setOpen] = React.useState(false);

  function handleDrawerClose() {
    setOpen(true);
  }

  function handleDrawerOpen() {
    setOpen(false);
  }

  return (
    <Fragment>
      <Hidden smDown>
        <Box className="ml-2">
          <Tooltip title="Toggle Drawer" placement="right">
            <IconButton
              color="inherit"
              onClick={handleDrawerClose}
              size="medium"
              className="btn-inverse">
              {open ? <MenuOpenRoundedIcon /> : <ListIcon />}
            </IconButton>
          </Tooltip>
        </Box>
        <Drawer
          anchor="right"
          open={open}
          variant="temporary"
          elevation={4}
          onClose={handleDrawerOpen}>
          <Box className="app-header-drawer">
            <PerfectScrollbar>
              <div className="p-4">
                <div className="text-center">
                  <div className="font-weight-bold font-size-lg mb-0 text-black">
                    Today's sales target
                  </div>
                  <p className="text-black-50">
                    Daily statistics regarding your sales targets
                  </p>
                </div>
                <div className="rounded p-4 text-white bg-deep-sky">
                  <div className="d-flex mb-3 justify-content-center">
                    <Circle
                      animate={true} // Boolean: Animated/Static progress
                      animationDuration="3s" //String: Length of animation
                      responsive={false} // Boolean: Make SVG adapt to parent size
                      size={160} // Number: Defines the size of the circle.
                      lineWidth={33} // Number: Defines the thickness of the circle's stroke.
                      progress={69.45} // Number: Update to change the progress and percentage.
                      progressColor="rgba(255,255,255,1)" // String: Color of "progress" portion of circle.
                      bgColor="rgba(255,255,255,.2)" // String: Color of "empty" portion of circle.
                      textColor="rgba(255,255,255,.7)" // String: Color of percentage text color.
                      percentSpacing={10} // Number: Adjust spacing of "%" symbol and number.
                      roundedStroke={true} // Boolean: Rounded/Flat line ends
                      showPercentage={true} // Boolean: Show/hide percentage.
                      showPercentageSymbol={true} // Boolean: Show/hide only the "%" symbol.
                    />
                  </div>
                  <div className="text-center">
                    <Button
                      size="medium"
                      color="inherit"
                      className="text-uppercase font-size-xs font-weight-bold">
                      Generate report
                    </Button>
                  </div>
                </div>
              </div>
              <Divider />
              <div className="p-4 text-center">
                <h3 className="mb-0 display-3 mt-1 font-weight-bold">
                  $
                  <span className="pr-1">
                    <CountUp
                      start={0}
                      end={458.695}
                      duration={4}
                      separator=""
                      delay={3}
                      decimals={3}
                      decimal=","
                      prefix=""
                      suffix=""
                    />
                  </span>
                </h3>
                <Chart
                  options={chart36Options}
                  series={chart36Data}
                  type="area"
                  height={120}
                />
                <Grid container spacing={4} className="mt-3">
                  <Grid item xs={6}>
                    <span className="opacity-6 pb-2">Current month</span>
                    <div className="d-flex align-items-center justify-content-center">
                      <span className="font-weight-bold font-size-lg">
                        <small className="opacity-6 pr-1">$</small>
                        46,362
                      </span>
                      <Badge color="secondary" className="ml-2 text-danger">
                        -8%
                      </Badge>
                    </div>
                  </Grid>
                  <Grid item xs={6}>
                    <span className="opacity-6 pb-2">Last year</span>
                    <div className="d-flex align-items-center justify-content-center">
                      <span className="font-weight-bold font-size-lg">
                        <small className="opacity-6 pr-1">$</small>
                        34,546
                      </span>
                      <Badge color="secondary" className="text-success ml-2">
                        +13%
                      </Badge>
                    </div>
                  </Grid>
                </Grid>
                <div className="pb-2 pt-4 text-center">
                  <Button size="small" variant="outlined" color="secondary">
                    <span className="btn-wrapper--label">
                      View complete report
                    </span>
                    <span className="btn-wrapper--icon">
                      <FontAwesomeIcon icon={['fas', 'arrow-right']} />
                    </span>
                  </Button>
                </div>
              </div>
              <Divider />
              <div className="p-4">
                <div className="text-center">
                  <div className="font-weight-bold font-size-lg mb-0 text-black">
                    Tasks
                  </div>
                  <p className="text-black-50">Your daily tasks list</p>
                </div>
                <div className="rounded p-3 bg-secondary">
                  <div className="task-wrapper">
                    <div className="task-item">
                      <div className="align-box-row">
                        <Checkbox className="align-self-center mr-2" />
                        <div>
                          <b>Finish tasks for today</b>
                          <p className="text-black-50 mt-1 mb-0">
                            But I must explain to you how all this mistaken
                            idea.
                          </p>
                        </div>
                      </div>
                    </div>
                    <div className="task-item">
                      <div className="align-box-row">
                        <Checkbox className="align-self-center mr-2" />
                        <div>
                          <b>Blinded by desire</b>
                          <p className="text-black-50 mt-1 mb-0">
                            A wonderful serenity has taken possession.
                          </p>
                          <div className="timeline-list mt-3">
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
                                  Mosaic, the first graphical browser, is
                                  introduced to the average consumer.
                                </p>
                                <div className="mt-3">
                                  <Button size="small" color="primary">
                                    Submit Report
                                  </Button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div className="task-item">
                      <div className="align-box-row">
                        <Checkbox className="align-self-center mr-2" />
                        <div className="w-100">
                          World among the stalks
                          <p className="text-black-50 mt-1">
                            Who formed us in his own image, and the breath.
                          </p>
                          <List>
                            <ListItem className="bg-white d-flex justify-content-between align-items-center p-4">
                              <div className="w-100">
                                <div className="d-flex flex-wrap justify-content-between mb-2">
                                  <small className="line-height-xs text-uppercase text-muted">
                                    Nov 12, 11:25am
                                  </small>
                                  <small className="line-height-xs text-uppercase text-success">
                                    Due in 12 days
                                  </small>
                                </div>
                                <h6 className="pt-1 pb-1">
                                  <a
                                    href="#/"
                                    onClick={e => e.preventDefault()}>
                                    #56 - Deploy new website
                                  </a>
                                </h6>
                                <p className="mb-3">
                                  This is a dummy text acting ...
                                </p>
                                <div className="d-flex justify-content-between align-items-center">
                                  <Badge color="primary" className="pl-3 pr-3">
                                    Files ready
                                  </Badge>
                                  <div>
                                    <Button size="small" color="primary">
                                      <span className="btn-wrapper--icon">
                                        <FontAwesomeIcon
                                          icon={['fas', 'plus']}
                                          className="font-size-sm"
                                        />
                                      </span>
                                      <span className="btn-wrapper--label">
                                        Add
                                      </span>
                                    </Button>
                                  </div>
                                </div>
                              </div>
                            </ListItem>
                            <ListItem className="d-flex justify-content-between align-items-center align-content-center pl-1">
                              <Tooltip
                                arrow
                                title="There are 653 new comments available!"
                                placement="top">
                                <Button size="small" color="primary">
                                  <span className="btn-wrapper--icon">
                                    <FontAwesomeIcon
                                      icon={['far', 'comments']}
                                    />
                                  </span>
                                  <span className="btn-wrapper--label ml-1">
                                    653
                                  </span>
                                </Button>
                              </Tooltip>
                              <div>
                                <small className="text-muted mr-1">(21)</small>
                                <FontAwesomeIcon
                                  icon={['fas', 'star']}
                                  className="text-warning"
                                />
                                <FontAwesomeIcon
                                  icon={['fas', 'star']}
                                  className="text-warning"
                                />
                                <FontAwesomeIcon
                                  icon={['fas', 'star']}
                                  className="text-warning"
                                />
                                <FontAwesomeIcon
                                  icon={['fas', 'star']}
                                  className="text-warning"
                                />
                                <FontAwesomeIcon
                                  icon={['fas', 'star']}
                                  className="text-warning"
                                />
                              </div>
                            </ListItem>
                          </List>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <Divider />
              <div className="p-4">
                <div className="text-center">
                  <div className="font-weight-bold font-size-lg mb-0 text-black">
                    Latest sales
                  </div>
                  <p className="text-black-50">Latest reports available</p>
                </div>
                <Grid container spacing={4}>
                  <Grid item xs={12} md={6}>
                    <div className="bg-secondary rounded py-2 px-1">
                      <div className="display-3 text-center line-height-sm text-second text-center d-flex align-items-center pt-3 justify-content-center">
                        <FontAwesomeIcon
                          icon={['far', 'dot-circle']}
                          className="font-size-sm text-warning mr-2"
                        />
                        <div>436</div>
                      </div>
                      <div className="text-black-50 text-center opacity-6 pt-3">
                        <b>+65%</b> increase
                      </div>
                    </div>
                  </Grid>
                  <Grid item xs={12} md={6}>
                    <div className="bg-secondary rounded py-2 px-1">
                      <div className="display-3 text-center line-height-sm text-second text-center d-flex align-items-center pt-3 justify-content-center">
                        <FontAwesomeIcon
                          icon={['fas', 'arrow-up']}
                          className="font-size-sm text-success mr-2"
                        />
                        <div>843</div>
                      </div>
                      <div className="text-black-50 text-center opacity-6 pt-3">
                        <b>-22%</b> decrease
                      </div>
                    </div>
                  </Grid>
                </Grid>
              </div>
            </PerfectScrollbar>
          </Box>
        </Drawer>
      </Hidden>
    </Fragment>
  );
}

export default HeaderDrawer;
