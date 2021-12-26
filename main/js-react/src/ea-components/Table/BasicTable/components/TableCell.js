import React from 'react';

import {
  Avatar,
  Tooltip,
  Chip,
  IconButton,
  CircularProgress,
  makeStyles
} from '@material-ui/core';
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

import DeleteOutlineIcon from '@material-ui/icons/DeleteOutline';
import EditIcon from '@material-ui/icons/Edit';
import FileCopyOutlinedIcon from '@material-ui/icons/FileCopyOutlined';

const CELL_TYPES = {
  TEXT: 'text',
  TEXT_W_LABEL: 'text_with_label',
  AVATARS: 'avatar',
  CHIPS: 'chips',
  ACTIONS: 'actions',
  COLOR: 'color'
};

const ICON_TYPES = {
  edit: <EditIcon />,
  delete: <DeleteOutlineIcon />,
  clone: <FileCopyOutlinedIcon />,
  processing: <CircularProgress size="1em" />
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

const getRandomColor = title => {
  let hash = 0;
  title
    .toUpperCase()
    .split('')
    .forEach(function(alphabet) {
      hash += alphabet.charCodeAt(0) - 64;
    });

  const rand = Math.floor(hash % COLORS.length);
  return COLORS[rand];
};

/**
 * ex. config {type: 'text', position: 'left', cellClass: 'some class'}
 */
const TextCell = (config, data) => {
  const { cellClass, position } = config;

  return (
    <td className={`ea-ellipsis text-${position} ${cellClass ?? ''}`}>
      {Array.isArray(data) ? (
        <div className="d-flex flex-column">
          {data.map(single => (
            <span className="ea-ellipsis">{single}</span>
          ))}
        </div>
      ) : (
        data
      )}
    </td>
  );
};

/**
 * ex. config {type: 'text', position: 'left', cellClass: 'some class'}
 */
const TextWithLabelCell = (config, data) => {
  const { cellClass, position } = config;

  return (
    <td className={`text-${position} ${cellClass ?? ''}`}>
      <div className="d-flex flex-column">
        {data.map(single => (
          <>
            <span className="ea-table-cell-label ea-ellipsis">
              {single.label}
            </span>
            <span className="ea-ellipsis">{single.text}</span>
          </>
        ))}
      </div>
    </td>
  );
};

const ColorCell = (config, data) => {
  const { cellClass, position } = config;
  return (
    <td className={`text-${position} ${cellClass ?? ''}`}>
      <div
        style={{
          color: '#fff',
          backgroundColor: data,
          height: '28px',
          width: '28px',
          borderRadius: '4px',
          textAlign: 'center',
          paddingTop: '4px'
        }}
      />
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
              style={{ backgroundColor: getRandomColor(single) }}
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
        {hidden.length > 1 && (
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

/**
 * ex. config {type: 'actions', position: 'center', cellClass: 'some class'}
 */
const ActionsCell = (config, data) => {
  const { cellClass, position } = config;

  return (
    <td className={`text-${position} ${cellClass ?? ''}`}>
      {data &&
        data.map(action => (
          <Tooltip arrow title={action.tooltip}>
            <IconButton className={action.className} onClick={action.action}>
              {ICON_TYPES[action.icon]}
            </IconButton>
          </Tooltip>
        ))}
    </td>
  );
};

export const renderCell = (config, data) => {
  switch (config.type) {
    case CELL_TYPES.TEXT:
      return TextCell(config, data);
    case CELL_TYPES.TEXT_W_LABEL:
      return TextWithLabelCell(config, data);
    case CELL_TYPES.AVATARS:
      return AvatarsCell(config, data);
    case CELL_TYPES.COLOR:
      return ColorCell(config, data);
    case CELL_TYPES.CHIPS:
      return ChipsCell(config, data);
    case CELL_TYPES.ACTIONS:
      return ActionsCell(config, data);
    default:
      return null;
  }
};
