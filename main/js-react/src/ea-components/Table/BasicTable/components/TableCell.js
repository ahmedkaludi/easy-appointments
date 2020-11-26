import React from 'react';

import { Avatar, Tooltip } from '@material-ui/core';
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

const CELL_TYPES = {
  TEXT: 'text',
  AVATAR: 'avatar'
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

const getRandomColor = () => {
  const rand = Math.floor(Math.random() * COLORS.length);
  return COLORS[rand];
};

/**
 * ex. config {type: 'text', position: 'left', cellClass: 'some class'}
 */
const TextCell = (config, data) => {
  const { cellClass, position } = config;

  return <td className={`text-${position} ${cellClass ?? ''}`}>{data}</td>;
};

/**
 * ex. config {type: 'avatar', position: 'center', cellClass: 'some class'}
 */
const AvatarsCell = (config, data) => {
  const { cellClass, position } = config;
  const visible = data.slice(0, 3);
  // const hidden = data.slice(3);

  return (
    <td className={`text-${position} ${cellClass ?? ''}`}>
      <AvatarGroup className="d-flex justify-content-center">
        {visible.map(worker => (
          <Tooltip arrow title={worker}>
            <Avatar
              src="/broken-image.jpg"
              style={{ backgroundColor: getRandomColor() }}
            />
          </Tooltip>
        ))}
      </AvatarGroup>
    </td>
  );
};

export const renderCell = (config, data) => {
  switch (config.type) {
    case CELL_TYPES.TEXT:
      return TextCell(config, data);
    case CELL_TYPES.AVATAR:
      return AvatarsCell(config, data);
    default:
      return null;
  }
};
