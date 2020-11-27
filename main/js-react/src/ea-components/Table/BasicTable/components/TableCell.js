import React from 'react';

import { Avatar, Tooltip, Chip, makeStyles } from '@material-ui/core';
import AvatarGroup from '@material-ui/lab/AvatarGroup';
import {
  teal,
  blueGrey,
  deepOrange,
  cyan,
  indigo,
  deepPurple,
  lightGreen,
  blue,
  lightBlue
} from '@material-ui/core/colors';
// import CalendarTodayIcon from '@material-ui/icons/CalendarToday';

const CELL_TYPES = {
  TEXT: 'text',
  AVATARS: 'avatar',
  CHIPS: 'chips'
};

const COLORS = [
  teal[300],
  blueGrey[300],
  deepOrange[300],
  cyan[300],
  indigo[300],
  deepPurple[300],
  lightGreen[300],
  blue[300],
  lightBlue[300]
];

const useStyles = makeStyles(theme => ({
  chipsWrap: {
    display: 'flex',
    justifyContent: 'center',
    '& > *': {
      margin: theme.spacing(0.3)
    }
  }
}));

const getRandomColor = () => {
  const rand = Math.floor(Math.random() * COLORS.length);
  return COLORS[rand];
};

/**
 * ex. config {type: 'text', position: 'left', cellClass: 'some class'}
 */
const TextCell = (config, data) => {
  const { cellClass, position } = config;

  return (
    <td className={`ea-ellipsis text-${position} ${cellClass ?? ''}`}>
      {data}
    </td>
  );
};

/**
 * ex. config {type: 'avatar', position: 'center', cellClass: 'some class'}
 */
const AvatarsCell = (config, data) => {
  const { cellClass, position } = config;
  const visible = data.slice(0, 2);
  const hidden = data.slice(2);

  return (
    <td className={`text-${position} ${cellClass ?? ''}`}>
      <AvatarGroup className="d-flex justify-content-center">
        {visible.map(single => (
          <Tooltip arrow title={single}>
            <Avatar
              src="/broken-image.jpg"
              style={{ backgroundColor: getRandomColor() }}
            />
          </Tooltip>
        ))}
        {hidden.length && (
          <Tooltip title={hidden.join(' • ')}>
            <Avatar>{`+${hidden.length}`}</Avatar>
          </Tooltip>
        )}
      </AvatarGroup>
    </td>
  );
};

/**
 * ex. config {type: 'chips', position: 'center', cellClass: 'some class'}
 */
const ChipsCell = (config, data) => {
  const { cellClass, position } = config;
  const visible = data && data.length ? data[0] : 'Unknown';
  const hidden = data && data.length ? data.slice(1) : [];

  const classes = useStyles();

  return (
    <td className={`text-${position} ${cellClass ?? ''}`}>
      <div className={classes.chipsWrap}>
        <Chip size="small" label={visible} color="primary" />
        {hidden.length && (
          <Tooltip title={hidden.join(' • ')}>
            <Chip
              size="small"
              label={`+${hidden.length} day${hidden.length > 1 ? 's' : ''}`}
              variant="outlined"
            />
          </Tooltip>
        )}
      </div>
    </td>
  );
};

export const renderCell = (config, data) => {
  switch (config.type) {
    case CELL_TYPES.TEXT:
      return TextCell(config, data);
    case CELL_TYPES.AVATARS:
      return AvatarsCell(config, data);
    case CELL_TYPES.CHIPS:
      return ChipsCell(config, data);
    default:
      return null;
  }
};
