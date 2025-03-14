import React, { Component } from 'react';
import { Provider } from 'react-redux';

import { ThemeProvider } from '@material-ui/styles';
import { SnackbarProvider } from 'notistack';

import MuiTheme from './theme';

import configureStore from './config/configureStore';
// import ScrollToTop from './utils/ScrollToTop';

import {
  VacationPage,
  LocationsPage,
  ServicesPage,
  WorkersPage,
  ConnectionsPage,
  ToolsPage,
  ReportsPage
} from './pages';

import './assets/base.scss';

import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';

// import CssBaseline from '@material-ui/core/CssBaseline';

// import {
//   fab,
//   faFacebook,
//   faTwitter,
//   faVuejs,
//   faReact,
//   faHtml5,
//   faGoogle,
//   faInstagram,
//   faPinterest,
//   faYoutube,
//   faDiscord,
//   faSlack,
//   faDribbble,
//   faGithub
// } from '@fortawesome/free-brands-svg-icons';
// import {
//   far,
//   faSquare,
//   faLifeRing,
//   faCheckCircle,
//   faTimesCircle,
//   faDotCircle,
//   faThumbsUp,
//   faComments,
//   faFolderOpen,
//   faTrashAlt,
//   faFileImage,
//   faFileArchive,
//   faCommentDots,
//   faFolder,
//   faKeyboard,
//   faCalendarAlt,
//   faEnvelope,
//   faAddressCard,
//   faMap,
//   faObjectGroup,
//   faImages,
//   faUser,
//   faLightbulb,
//   faGem,
//   faClock,
//   faUserCircle,
//   faQuestionCircle,
//   faBuilding,
//   faBell,
//   faFileExcel,
//   faFileAudio,
//   faFileVideo,
//   faFileWord,
//   faFilePdf,
//   faFileCode,
//   faFileAlt,
//   faEye,
//   faChartBar
// } from '@fortawesome/free-regular-svg-icons';
// import {
//   fas,
//   faAngleDoubleRight,
//   faAngleDoubleLeft,
//   faSmile,
//   faHeart,
//   faBatteryEmpty,
//   faBatteryFull,
//   faChevronRight,
//   faSitemap,
//   faPrint,
//   faMapMarkedAlt,
//   faTachometerAlt,
//   faAlignCenter,
//   faExternalLinkAlt,
//   faShareSquare,
//   faInfoCircle,
//   faSync,
//   faQuoteRight,
//   faStarHalfAlt,
//   faShapes,
//   faCarBattery,
//   faTable,
//   faCubes,
//   faPager,
//   faCameraRetro,
//   faBomb,
//   faNetworkWired,
//   faBusAlt,
//   faBirthdayCake,
//   faEyeDropper,
//   faUnlockAlt,
//   faDownload,
//   faAward,
//   faPlayCircle,
//   faReply,
//   faUpload,
//   faBars,
//   faEllipsisV,
//   faSave,
//   faSlidersH,
//   faCaretRight,
//   faChevronUp,
//   faPlus,
//   faLemon,
//   faChevronLeft,
//   faTimes,
//   faChevronDown,
//   faFilm,
//   faSearch,
//   faEllipsisH,
//   faCog,
//   faArrowsAltH,
//   faPlusCircle,
//   faAngleRight,
//   faAngleUp,
//   faAngleLeft,
//   faAngleDown,
//   faArrowUp,
//   faArrowDown,
//   faArrowRight,
//   faArrowLeft,
//   faStar,
//   faSignOutAlt,
//   faLink
// } from '@fortawesome/free-solid-svg-icons';
// library.add(
//   far,
//   faSquare,
//   faLifeRing,
//   faCheckCircle,
//   faTimesCircle,
//   faDotCircle,
//   faThumbsUp,
//   faComments,
//   faFolderOpen,
//   faTrashAlt,
//   faFileImage,
//   faFileArchive,
//   faCommentDots,
//   faFolder,
//   faKeyboard,
//   faCalendarAlt,
//   faEnvelope,
//   faAddressCard,
//   faMap,
//   faObjectGroup,
//   faImages,
//   faUser,
//   faLightbulb,
//   faGem,
//   faClock,
//   faUserCircle,
//   faQuestionCircle,
//   faBuilding,
//   faBell,
//   faFileExcel,
//   faFileAudio,
//   faFileVideo,
//   faFileWord,
//   faFilePdf,
//   faFileCode,
//   faFileAlt,
//   faEye,
//   faChartBar
// );
//
// library.add(
//   fab,
//   faFacebook,
//   faTwitter,
//   faVuejs,
//   faReact,
//   faHtml5,
//   faGoogle,
//   faInstagram,
//   faPinterest,
//   faYoutube,
//   faDiscord,
//   faSlack,
//   faDribbble,
//   faGithub
// );
//
// library.add(
//   fas,
//   faAngleDoubleRight,
//   faAngleDoubleLeft,
//   faSmile,
//   faHeart,
//   faBatteryEmpty,
//   faBatteryFull,
//   faChevronRight,
//   faSitemap,
//   faPrint,
//   faMapMarkedAlt,
//   faTachometerAlt,
//   faAlignCenter,
//   faExternalLinkAlt,
//   faShareSquare,
//   faInfoCircle,
//   faSync,
//   faQuoteRight,
//   faStarHalfAlt,
//   faShapes,
//   faCarBattery,
//   faTable,
//   faCubes,
//   faPager,
//   faCameraRetro,
//   faBomb,
//   faNetworkWired,
//   faBusAlt,
//   faBirthdayCake,
//   faEyeDropper,
//   faUnlockAlt,
//   faDownload,
//   faAward,
//   faPlayCircle,
//   faReply,
//   faUpload,
//   faBars,
//   faEllipsisV,
//   faSave,
//   faSlidersH,
//   faCaretRight,
//   faChevronUp,
//   faPlus,
//   faLemon,
//   faChevronLeft,
//   faTimes,
//   faChevronDown,
//   faFilm,
//   faSearch,
//   faEllipsisH,
//   faCog,
//   faArrowsAltH,
//   faPlusCircle,
//   faAngleRight,
//   faAngleUp,
//   faAngleLeft,
//   faAngleDown,
//   faArrowUp,
//   faArrowDown,
//   faArrowRight,
//   faArrowLeft,
//   faStar,
//   faSignOutAlt,
//   faLink
// );
//
library.add(fas);
library.add(far);

const store = configureStore();

const PAGES = {
  vacations: <VacationPage />,
  locations: <LocationsPage />,
  services: <ServicesPage />,
  workers: <WorkersPage />,
  connections: <ConnectionsPage />,
  tools: <ToolsPage />,
  reports: <ReportsPage />
};

class EasyApp extends Component {
  render() {
    return (
      <Provider store={store}>
        <ThemeProvider theme={MuiTheme}>
          <SnackbarProvider
            anchorOrigin={{ horizontal: 'right', vertical: 'bottom' }}
            maxSnack={5}>
            {PAGES[this.props.page] ?? null}
          </SnackbarProvider>
        </ThemeProvider>
      </Provider>
    );
  }
}

export default EasyApp;
