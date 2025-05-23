import React from 'react';
import ReactDOM from 'react-dom';

import EasyApp from './EasyApp';

if (document.getElementById('ea-admin-vacation')) {
  ReactDOM.render(
    <EasyApp page="vacations" />,
    document.getElementById('ea-admin-vacation')
  );
}

if (document.getElementById('ea-admin-locations')) {
  ReactDOM.render(
    <EasyApp page="locations" />,
    document.getElementById('ea-admin-locations')
  );
}

if (document.getElementById('ea-admin-services')) {
  ReactDOM.render(
    <EasyApp page="services" />,
    document.getElementById('ea-admin-services')
  );
}

if (document.getElementById('ea-admin-workers')) {
  ReactDOM.render(
    <EasyApp page="workers" />,
    document.getElementById('ea-admin-workers')
  );
}

if (document.getElementById('ea-admin-connections')) {
  ReactDOM.render(
    <EasyApp page="connections" />,
    document.getElementById('ea-admin-connections')
  );
}

if (document.getElementById('ea-admin-tools')) {
  ReactDOM.render(
    <EasyApp page="tools" />,
    document.getElementById('ea-admin-tools')
  );
}

if (document.getElementById('ea-admin-reports')) {
  ReactDOM.render(
    <EasyApp page="reports" />,
    document.getElementById('ea-admin-reports')
  );
}
