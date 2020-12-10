import React from 'react';
import ReactDOM from 'react-dom';

import EasyApp from './EasyApp';

import { WorkersPage } from './pages/Workers';
import { ServicesPage } from './pages/Services';
import { LocationsPage } from './pages/Locations';

if (document.getElementById('ea-admin-vacation')) {
  ReactDOM.render(<EasyApp />, document.getElementById('ea-admin-vacation'));
}

if (document.getElementById('ea-admin-locations')) {
  ReactDOM.render(
    <LocationsPage />,
    document.getElementById('ea-admin-locations')
  );
}

if (document.getElementById('ea-admin-workers')) {
  ReactDOM.render(<WorkersPage />, document.getElementById('ea-admin-workers'));
}

if (document.getElementById('ea-admin-services')) {
  ReactDOM.render(
    <ServicesPage />,
    document.getElementById('ea-admin-services')
  );
}
