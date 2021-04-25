import React from 'react';

import { Button } from '../../../../../../ea-components';
import { applyFormProps } from '../../../../../../ea-components/Form/applyFormProps';

const ExportBtn = ({ loading, setLoading, model, updateValue }) => {
  const formatDate = React.useCallback(date => {
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    return date.toJSON().slice(0, 10);
  }, []);

  const onSave = async () => {
    setLoading(true);
    await new Promise(resolve =>
      setTimeout(() => {
        updateValue('columns', null);
        updateValue('ea-export-from', formatDate(new Date()));
        updateValue('ea-export-to', formatDate(new Date()));
        resolve();
      }, 2000)
    );
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
