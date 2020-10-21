import React, { createContext, Component } from 'react';
import PropTypes from 'prop-types';

import { Divider } from '@material-ui/core';
import Button from '../FormFields/Button';

export const FormContext = createContext({});

class Form extends Component {
  static propTypes = {
    children: PropTypes.oneOfType([
      PropTypes.node,
      PropTypes.arrayOf(PropTypes.node)
    ]),
    customClass: PropTypes.string,
    onCancel: PropTypes.func,
    onSave: PropTypes.func,
    model: PropTypes.objectOf(PropTypes.any)
  };

  static defaultProps = {
    children: null,
    customClass: '',
    onCancel: f => f,
    onSave: f => f,
    model: null
  };

  constructor(props) {
    super(props);

    this.validators = {};
    this.clearForm = this.clearForm.bind(this);
    this.onSave = this.onSave.bind(this);
    this.onCancel = this.onCancel.bind(this);
    this.updateValue = this.updateValue.bind(this);
    this.subscribeValidator = this.subscribeValidator.bind(this);
    this.unsubscribeValidator = this.unsubscribeValidator.bind(this);
    this.validate = this.validate.bind(this);
    this.setFieldErrors = this.setFieldErrors.bind(this);
    this.setLoading = this.setLoading.bind(this);

    this.state = {
      loading: false,
      model: {},
      fieldErrors: {},
      clearForm: this.clearForm,
      updateValue: this.updateValue,
      subscribeValidator: this.subscribeValidator,
      unsubscribeValidator: this.unsubscribeValidator,
      validate: this.validate,
      setFieldErrors: this.setFieldErrors,
      setLoading: this.setLoading
    };
  }

  componentDidMount() {
    if (this.props.model) {
      this.setState({ model: this.props.model });
    }
  }

  setLoading(isLoading) {
    this.setState({ loading: isLoading });
  }

  updateValue(name, value) {
    this.setState(state => {
      const fieldErrors = { ...state.fieldErrors };
      delete fieldErrors[name];

      return { model: { ...state.model, [name]: value }, fieldErrors };
    });
  }

  subscribeValidator(key, validator) {
    if (typeof validator !== 'function' || typeof key !== 'string') {
      return;
    }

    this.validators[key] = validator;
  }

  unsubscribeValidator(key) {
    delete this.validators[key];
  }

  validate() {
    return Object.keys(this.validators)
      .map(validatorKey => this.validators[validatorKey]())
      .reduce((isValid, current) => isValid && current, true);
  }

  setFieldErrors(field, errors) {
    this.setState(prevState => {
      const fieldErrors = { ...prevState.fieldErrors };

      if ((!Array.isArray(errors) || !errors.length) && fieldErrors[field]) {
        delete fieldErrors[field];
        return { fieldErrors };
      }

      fieldErrors[field] = errors;

      return { fieldErrors };
    });
  }

  clearForm() {
    this.setState({ model: {}, loading: false, fieldErrors: {} });
  }

  async onSave() {
    await this.props.onSave();
    this.clearForm();
  }

  async onCancel() {
    await this.props.onCancel();
    this.clearForm();
  }

  render() {
    const { children, customClass } = this.props;
    const { loading } = this.state;
    const classes = `${customClass}${loading ? ' loading' : ''}`;

    return (
      <FormContext.Provider value={this.state}>
        <div className={`ea-form ${classes}`}>
          <div className="ea-form--fields">{children}</div>
          <div className="ea-form--footer">
            <Divider />
            <div className="ea-form--actions">
              <Button
                label="Cancel"
                variant="outlined"
                onClick={this.onCancel}
              />
              <Button label="Save" color="primary" onClick={this.onSave} />
            </div>
          </div>
        </div>
      </FormContext.Provider>
    );
  }
}

export default Form;
