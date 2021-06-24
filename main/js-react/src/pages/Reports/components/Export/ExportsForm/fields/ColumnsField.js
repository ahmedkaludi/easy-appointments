import React from 'react';
import PropTypes from 'prop-types';

import { orderBy } from 'lodash';
import { useSnackbar } from 'notistack';
import { Tooltip, IconButton } from '@material-ui/core';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { Field, Autocomplete } from '../../../../../../ea-components';
import { getSettings, __, setSettings } from '../../../../../../services';
import { ReportExportCommunicator } from '../../../../../../communicators';

const COLUMNS = getSettings('export_tags_list', []).map((col, index) => ({
  id: index,
  value: col,
  label: col
}));

const Columns = ({ value, updateFieldValue, error }) => {
  const { enqueueSnackbar } = useSnackbar();

  React.useEffect(function setInitialColumns() {
    const savedColumns = getSettings('saved_tags_list', '');
    updateFieldValue(savedColumns);
  }, []);

  const onChange = (e, newVal) => {
    const newColumns = orderBy(newVal, ['id'], ['asc'])
      .map(val => val.value)
      .join(',');

    // save form value
    updateFieldValue(newColumns);
  };

  const saveSettings = async () => {
    try {
      await ReportExportCommunicator.saveExportColumns(value);

      setSettings('saved_tags_list', value);

      enqueueSnackbar('Your columns settings are saved successfully!', {
        variant: 'success'
      });
    } catch (e) {
      enqueueSnackbar('Saving columns failed! Please try again.', {
        variant: 'error'
      });
    }
  };

  const selected = value
    ? value.split(',').map(column => COLUMNS.find(opt => opt.value === column))
    : [];

  return (
    <div className="d-flex align-items-center">
      <Autocomplete
        label={__('Columns *', 'easy-appointments')}
        placeholder={__('Column...', 'easy-appointments')}
        value={selected}
        onChange={onChange}
        options={COLUMNS}
        error={error}
        customClass="flex-fill mr-3"
      />
      <Tooltip title="Save settings" arrow>
        <IconButton size="small" onClick={saveSettings}>
          <FontAwesomeIcon
            className="save-settings"
            icon={['fas', 'save']}
            size="lg"
          />
        </IconButton>
      </Tooltip>
    </div>
  );
};

Columns.propTypes = {
  value: PropTypes.string,
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Columns.defaultProps = {
  value: null,
  updateFieldValue: f => f
};

export const ColumnsField = () => (
  <Field name="columns" component={props => <Columns {...props} />} required />
);
