import React, { Fragment, useState, useEffect } from 'react';

import { __ } from '../../services/Localization';
import { ServicesForm } from './components/ServicesForm';
import { ServicesTable } from './components/ServicesTable';
import { ServicesCommunicator } from '../../communicators';
import {
  PageTitle,
  Sidebar,
  PageContentWrap,
  PAGE_KEYS
} from '../../ea-components';

const ServicesPage = () => {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [services, setServices] = useState([]);
  const [activeService, setActiveService] = useState(null);
  const [processing, setProcessing] = useState(null);

  const loadServices = async () => {
    try {
      const records = await ServicesCommunicator.fetchAll();
      setServices(records);
      setLoading(false);
    } catch (e) {
      throw new Error(e);
    }
  };

  useEffect(() => {
    loadServices();
  }, []);

  const toggleSidebar = () => {
    if (open && activeService) {
      setActiveService(null);
    }

    setOpen(!open);
  };

  const onCreate = async model => {
    try {
      const result = await ServicesCommunicator.save(model);
      toggleSidebar();
      setServices([{ ...model, id: result.id }, ...services]);
    } catch (e) {
      throw new Error(e);
    }
  };

  const onEdit = async model => {
    try {
      await ServicesCommunicator.save(model);

      const data = services.map(record =>
        record.id === model.id ? model : record
      );
      toggleSidebar();
      setServices(data);
    } catch (e) {
      throw new Error(e);
    }
  };

  const save = (model, isEdit) => (isEdit ? onEdit(model) : onCreate(model));

  const onEditClick = row => {
    setActiveService(row);
    toggleSidebar();
  };

  const onDeleteClick = async row => {
    setProcessing(row?.id);

    try {
      const data = services.filter(record => record.id !== row.id);
      const result = await ServicesCommunicator.delete(row.id);

      if (result) {
        setServices(data);
      }
    } catch (e) {
      throw new Error(e);
    }

    setProcessing(null);
  };

  const headerAction = {
    callback: toggleSidebar,
    icon: 'briefcase',
    text: __('Add service', 'easy-appointments')
  };

  const title = activeService
    ? __('Edit services', 'easy-appointments')
    : __('Add services', 'easy-appointments');

  return (
    <Fragment>
      <PageTitle
        titleHeading={__('Services', 'easy-appointments')}
        action={headerAction}
      />

      <PageContentWrap
        pageKey={PAGE_KEYS.SERVICES}
        loading={loading}
        condition={services.length}>
        <ServicesTable
          data={services}
          onEdit={onEditClick}
          onDelete={onDeleteClick}
          onSort={loadServices}
          processing={processing}
        />
      </PageContentWrap>

      <Sidebar title={title} open={open} onClose={toggleSidebar}>
        <ServicesForm
          model={activeService}
          onSave={save}
          onCancel={toggleSidebar}
        />
      </Sidebar>
    </Fragment>
  );
};

export default ServicesPage;
