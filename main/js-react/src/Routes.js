import React, { lazy, Suspense, Fragment } from 'react';
import { Switch, Route, Redirect, useLocation } from 'react-router-dom';
import { AnimatePresence, motion } from 'framer-motion';

import { ThemeProvider } from '@material-ui/styles';

import { ClimbingBoxLoader } from 'react-spinners';

import MuiTheme from './theme';

// Layout Blueprints

import {
  LeftSidebar,
  MinimalLayout,
  PresentationLayout
} from './layout-blueprints';

// Example Pages

import PagesLogin from './example-pages/PagesLogin';
import PagesRegister from './example-pages/PagesRegister';
import PagesRecoverPassword from './example-pages/PagesRecoverPassword';
import PagesError404 from './example-pages/PagesError404';
import Buttons from './example-pages/Buttons';
import RegularTables1 from './example-pages/RegularTables1';

const DashboardDefault = lazy(() => import('./example-pages/DashboardDefault'));
const LandingPage = lazy(() => import('./example-pages/LandingPage'));

const Routes = () => {
  const location = useLocation();

  const pageVariants = {
    initial: {
      opacity: 0,
      scale: 0.99
    },
    in: {
      opacity: 1,
      scale: 1
    },
    out: {
      opacity: 0,
      scale: 1.01
    }
  };

  const pageTransition = {
    type: 'tween',
    ease: 'anticipate',
    duration: 0.4
  };

  const SuspenseLoading = () => {
    return (
      <Fragment>
        <div className="d-flex align-items-center flex-column vh-100 justify-content-center text-center py-3">
          <div className="d-flex align-items-center flex-column px-4">
            <ClimbingBoxLoader color={'#5383ff'} loading={true} />
          </div>
          <div className="text-muted font-size-xl text-center pt-3">
            Please wait while we load the live preview examples
            <span className="font-size-lg d-block text-dark">
              This live preview instance can be slower than a real production
              build!
            </span>
          </div>
        </div>
      </Fragment>
    );
  };
  return (
    <ThemeProvider theme={MuiTheme}>
      <AnimatePresence>
        <Suspense fallback={<SuspenseLoading />}>
          <Switch>
            <Redirect exact from="/" to="/LandingPage" />
            <Route path={['/LandingPage']}>
              <PresentationLayout>
                <Switch location={location} key={location.pathname}>
                  <motion.div
                    initial="initial"
                    animate="in"
                    exit="out"
                    variants={pageVariants}
                    transition={pageTransition}>
                    <Route path="/LandingPage" component={LandingPage} />
                  </motion.div>
                </Switch>
              </PresentationLayout>
            </Route>

            <Route
              path={[
                '/PagesLogin',
                '/PagesRegister',
                '/PagesRecoverPassword',
                '/PagesError404'
              ]}>
              <MinimalLayout>
                <Switch location={location} key={location.pathname}>
                  <motion.div
                    initial="initial"
                    animate="in"
                    exit="out"
                    variants={pageVariants}
                    transition={pageTransition}>
                    <Route path="/PagesLogin" component={PagesLogin} />
                    <Route path="/PagesRegister" component={PagesRegister} />
                    <Route
                      path="/PagesRecoverPassword"
                      component={PagesRecoverPassword}
                    />
                    <Route path="/PagesError404" component={PagesError404} />
                  </motion.div>
                </Switch>
              </MinimalLayout>
            </Route>

            <Route path={['/DashboardDefault', '/Buttons', '/RegularTables1']}>
              <LeftSidebar>
                <Switch location={location} key={location.pathname}>
                  <motion.div
                    initial="initial"
                    animate="in"
                    exit="out"
                    variants={pageVariants}
                    transition={pageTransition}>
                    <Route
                      path="/DashboardDefault"
                      component={DashboardDefault}
                    />
                    <Route path="/Buttons" component={Buttons} />
                    <Route path="/RegularTables1" component={RegularTables1} />
                  </motion.div>
                </Switch>
              </LeftSidebar>
            </Route>
          </Switch>
        </Suspense>
      </AnimatePresence>
    </ThemeProvider>
  );
};

export default Routes;
