import React, { Fragment } from 'react';
import PropTypes from 'prop-types';

import { __ } from '../../services/Localization';
import { Loader, EmptyState } from '../';

const CONFIG = {
  VACATIONS: {
    loadingText: 'Loading vacations',
    esType: 'vacation',
    esMessage: 'There are no scheduled vacation days yet.',
    esHint: `Use the 'Add vacation' button to add new vacation days.`
  },
  LOCATIONS: {
    loadingText: 'Loading locations',
    esType: 'location',
    esMessage: 'There are no available locations yet.',
    esHint: `Use the 'Add location' button to add new location.`
  },
  SERVICES: {
    loadingText: 'Loading services',
    esType: 'service',
    esMessage: 'There are no available services yet.',
    esHint: `Use the 'Add service' button to add new service.`
  },
  WORKERS: {
    loadingText: 'Loading workers',
    esType: 'worker',
    esMessage: 'There are no employees yet.',
    esHint: `Use the 'Add employee' button to add new employee.`
  },
  TOOLS: {
    loadingText: 'Loading tools',
    esType: 'tools',
    esMessage: 'There are no available tools yet.',
    esHint: `Use the 'Add tool' button to add new tool.`
  },
  CONNECTIONS: {
    loadingText: 'Loading connections',
    esType: 'connection',
    esMessage: 'There are no available connections yet.',
    esHint: `Use the 'Add connection' button to add new connection.`
  }
};

const PAGE_KEYS = Object.keys(CONFIG).reduce((obj, item) => {
  obj[item] = item;
  return obj;
}, {});

const PageContentWrap = ({ pageKey, loading, condition, children }) => {
  const config = CONFIG[pageKey];

  return (
    <Fragment>
      {loading ? (
        <Loader text={__(config.loadingText, 'easy-appointments')} />
      ) : !condition ? (
        <EmptyState
          type={config.esType}
          message={__(config.esMessage, 'easy-appointments')}
          hint={__(config.esHint, 'easy-appointments')}
        />
      ) : (
        children
      )}
    </Fragment>
  );
};

PageContentWrap.propTypes = {
  pageKey: PropTypes.string.isRequired,
  loading: PropTypes.bool,
  condition: PropTypes.bool,
  children: PropTypes.oneOfType([
    PropTypes.node,
    PropTypes.arrayOf(PropTypes.node)
  ])
};

PageContentWrap.defaultProps = {
  loading: true,
  condition: false,
  children: null
};

export { PageContentWrap, PAGE_KEYS };
