import React, { createContext, Component } from 'react';
import PropTypes from 'prop-types';

import { Divider } from '@material-ui/core';

export const FormContext = createContext({});

class Form extends Component {
  static propTypes = {
    children: PropTypes.oneOfType([
      PropTypes.node,
      PropTypes.arrayOf(PropTypes.node)
    ]),
    customClass: PropTypes.string
  };

  static defaultProps = {
    children: null,
    customClass: ''
  };

  constructor(props) {
    super(props);

    this.state = {
      loading: false
    };
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
              <span>Nothing to click yet</span>
            </div>
          </div>
        </div>
      </FormContext.Provider>
    );
  }
}

export default Form;
