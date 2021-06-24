import React from 'react';

import Drawer from '@material-ui/core/Drawer';
import Button from '@material-ui/core/Button';
import Divider from '@material-ui/core/Divider';
import { Select } from '../../../../../ea-components';
import { __, DataService } from '../../../../../services';

export const TimetableFilter = ({ open, onChange, filters }) => {
  const [locations, setLocations] = React.useState([]);
  const [services, setServices] = React.useState([]);
  const [workers, setWorkers] = React.useState([]);
  const [selection, setSelection] = React.useState({});

  const loadOptions = React.useCallback((key, setter) => {
    let opts = DataService.get(key);

    opts = opts ? opts.map(opt => ({ value: opt.id, label: opt.name })) : [];

    setter(opts);
  }, []);

  React.useEffect(() => {
    loadOptions('Locations', setLocations);
    loadOptions('Services', setServices);
    loadOptions('Workers', setWorkers);
  }, []);

  React.useEffect(() => {
    if (!open) return;

    setSelection(filters);
  }, [open]);

  const onChangeFilter = (e, key, options) => {
    setSelection(prev => ({
      ...prev,
      [key]: options.find(opt => `${opt.value}` === `${e.target.value}`)
    }));
  };

  const onApplyFilter = () => {
    onChange(selection);
    setSelection({});
  };

  return (
    <Drawer className="drawer" variant="persistent" anchor="left" open={open}>
      <div className="pr-3">
        <Select
          value={selection?.location?.value || ''}
          onChange={e => onChangeFilter(e, 'location', locations)}
          label={__('Location')}
          options={locations}
          customClass="standalone mt-2 mb-3"
        />
        <Select
          value={selection?.service?.value || ''}
          onChange={e => onChangeFilter(e, 'service', services)}
          label={__('Service')}
          options={services}
          customClass="standalone mb-3"
        />
        <Select
          value={selection?.worker?.value || ''}
          onChange={e => onChangeFilter(e, 'worker', workers)}
          label={__('Worker')}
          options={workers}
          customClass="standalone mb-3"
        />
        <Divider variant="middle" className="mx-0" />
        <div className="d-flex justify-content-around mt-3">
          <Button
            variant="outlined"
            size="small"
            onClick={() => onChange(filters)}>
            Cancel
          </Button>
          <Button
            variant="outlined"
            color="primary"
            size="small"
            onClick={onApplyFilter}>
            Apply
          </Button>
        </div>
      </div>
    </Drawer>
  );
};
