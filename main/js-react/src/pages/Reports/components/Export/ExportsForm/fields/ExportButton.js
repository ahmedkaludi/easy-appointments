import React from 'react';

import fileDownload from 'js-file-download';
import { useSnackbar } from 'notistack';
import { getSettings } from '../../../../../../services';
import { ReportExportCommunicator } from '../../../../../../communicators';
import { Button } from '../../../../../../ea-components';
import { applyFormProps } from '../../../../../../ea-components/Form/applyFormProps';

const ExportBtn = ({ loading, setLoading, model, updateValue }) => {
  const { enqueueSnackbar } = useSnackbar();

  const formatDate = React.useCallback(date => {
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    return date.toJSON().slice(0, 10);
  }, []);

  const onSuccess = () => {
    updateValue('columns', getSettings('saved_tags_list', ''));
    updateValue('ea-export-from', formatDate(new Date()));
    updateValue('ea-export-to', formatDate(new Date()));
  };

  const onSave = async () => {
    setLoading(true);

    try {
      const file = await ReportExportCommunicator.getExportFile(
        model['ea-export-from'],
        model['ea-export-to']
      );

      fileDownload(file, `export_${formatDate(new Date())}.csv`);

      onSuccess();
    } catch (e) {
      enqueueSnackbar('Export failed! Please try again.', {
        variant: 'error'
      });
    }

    setLoading(false);
  };

  return (
    <div className="ea-form--actions d-flex justify-content-center">
      <Button
        label="Export data"
        color="primary"
        onClick={onSave}
        disabled={loading || !model.columns}
        loading={loading}
      />
    </div>
  );
};

export const ExportButton = applyFormProps(ExportBtn);
