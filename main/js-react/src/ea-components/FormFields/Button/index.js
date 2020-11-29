import React from 'react';
import PropTypes from 'prop-types';

import { Button as MuiButton } from '@material-ui/core';

const Button = ({
  label,
  variant, // outlined, contained
  disabled,
  size, // small, medium, large
  color,
  onClick,
  customClass
}) => {
  const setConditionalProps = () => {
    const conditionalProps = {};

    if (color) {
      conditionalProps.color = color;
    }

    return conditionalProps;
  };

  return (
    <div className={`ea-button ${customClass ? customClass : ''}`}>
      <MuiButton
        variant={variant}
        disableElevation
        disabled={disabled}
        size={size}
        {...setConditionalProps()}
        onClick={onClick}>
        {label}
      </MuiButton>
    </div>
  );
};

Button.propTypes = {
  label: PropTypes.oneOfType([PropTypes.string, PropTypes.node]),
  variant: PropTypes.string,
  disabled: PropTypes.bool,
  size: PropTypes.string,
  onClick: PropTypes.func,
  customClass: PropTypes.string,
  color: PropTypes.string
};

Button.defaultProps = {
  label: '',
  variant: 'contained',
  disabled: false,
  size: 'medium',
  onClick: f => f,
  customClass: '',
  color: null
};

export default Button;
