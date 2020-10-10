import BarChartIcon from '@material-ui/icons/BarChart';
import CalendarTodayIcon from '@material-ui/icons/CalendarToday';
import ChatIcon from '@material-ui/icons/ChatOutlined';
import CodeIcon from '@material-ui/icons/Code';
import DashboardIcon from '@material-ui/icons/DashboardOutlined';
import ErrorIcon from '@material-ui/icons/ErrorOutline';
import FolderIcon from '@material-ui/icons/FolderOutlined';
import DashboardTwoToneIcon from '@material-ui/icons/DashboardTwoTone';
import GradeTwoTone from '@material-ui/icons/GradeTwoTone';
import ListAltIcon from '@material-ui/icons/ListAlt';
import LockOpenIcon from '@material-ui/icons/LockOpenOutlined';
import MailIcon from '@material-ui/icons/MailOutlined';
import PresentToAllIcon from '@material-ui/icons/PresentToAll';
import PeopleIcon from '@material-ui/icons/PeopleOutlined';
import PersonIcon from '@material-ui/icons/PersonOutlined';
import ReceiptIcon from '@material-ui/icons/ReceiptOutlined';
import SettingsIcon from '@material-ui/icons/SettingsOutlined';
import ViewModuleIcon from '@material-ui/icons/ViewModule';

var iconsMap = {
  BarChartIcon: BarChartIcon,
  CalendarTodayIcon: CalendarTodayIcon,
  ChatIcon: ChatIcon,
  CodeIcon: CodeIcon,
  DashboardIcon: DashboardIcon,
  ErrorIcon: ErrorIcon,
  FolderIcon: FolderIcon,
  DashboardTwoToneIcon: DashboardTwoToneIcon,
  GradeTwoTone: GradeTwoTone,
  ListAltIcon: ListAltIcon,
  LockOpenIcon: LockOpenIcon,
  MailIcon: MailIcon,
  PresentToAllIcon: PresentToAllIcon,
  PeopleIcon: PeopleIcon,
  PersonIcon: PersonIcon,
  ReceiptIcon: ReceiptIcon,
  SettingsIcon: SettingsIcon,
  ViewModuleIcon: ViewModuleIcon
};

export default [
  {
    label: 'Navigation menu',
    content: JSON.parse(
      `[
  {
    "label": "Dashboards",
    "icon": "DashboardTwoToneIcon",
    "content": [
      {
        "label": "Default",
        "description": "This is a dashboard page example built using this template.",
        "to": "/DashboardDefault"
      }
    ]
  },
  {
    "label": "Pages",
    "icon": "CalendarTodayIcon",
    "content": [
      {
        "label": "Login",
        "description": "This page can be used to create a login section for your users.",
        "to": "/PagesLogin"
      },
      {
        "label": "Register",
        "description": "This page can be used to create a register section for your users.",
        "to": "/PagesRegister"
      },
      {
        "label": "Recover Password",
        "description": "This page can be used to create a recover password section for your users.",
        "to": "/PagesRecoverPassword"
      },
      {
        "label": "Error 404",
        "description": "You can redirect your users to this page in case their encounter a 404 error.",
        "to": "/PagesError404"
      }
    ]
  },
  {
    "label": "Elements",
    "icon": "SettingsIcon",
    "content": [
      {
        "label": "Buttons",
        "description": "Wide selection of buttons that feature different styles for backgrounds, borders and hover options!",
        "to": "/Buttons"
      }
    ]
  },
  {
    "label": "Presentation Blocks",
    "icon": "ErrorIcon",
    "content": [
      {
        "label": "Landing page",
        "description": "",
        "to": "/LandingPage"
      }
    ]
  },
  {
    "label": "Regular Tables",
    "icon": "CodeIcon",
    "content": [
      {
        "label": "Tables examples 1",
        "description": "Tables are the backbone of almost all web applications.",
        "to": "/RegularTables1"
      }
    ]
  }
]`,
      (key, value) => {
        if (key === 'icon') {
          return iconsMap[value];
        } else {
          return value;
        }
      }
    )
  }
];
