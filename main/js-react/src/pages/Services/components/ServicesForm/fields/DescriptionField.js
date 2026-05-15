import React, { useEffect, useRef } from 'react';
import PropTypes from 'prop-types';

import { _x } from '../../../../../services/Localization';
import { Field } from '../../../../../ea-components';

const Description = ({ value, updateFieldValue, error }) => {
  const editorRef = useRef(null);

  const editorIdRef = useRef(
    'ea-description-' +
      Math.random()
        .toString(36)
        .substr(2, 9)
  );

  useEffect(() => {
    const editorId = editorIdRef.current;

    if (!window.wp?.editor) {
      return undefined;
    }

    if (window.tinymce?.get(editorId)) {
      return undefined;
    }

    window.wp.editor.initialize(editorId, {
      tinymce: {
        height: 250,
        menubar: false,
        branding: false,
        plugins: 'lists link paste wordpress wpautoresize',
        toolbar:
          'bold italic underline | bullist numlist | link unlink | undo redo',

        setup: function(editor) {
          editorRef.current = editor;

          editor.on('change keyup', function() {
            updateFieldValue(editor.getContent());
          });
        }
      },

      quicktags: true,
      mediaButtons: false
    });

    return () => {
      const editor = window.tinymce?.get(editorId);

      if (editor) {
        editor.remove();
      }
    };
  }, []);

  // IMPORTANT FIX
  useEffect(() => {
    const editor = editorRef.current;

    if (!editor) {
      return;
    }

    const currentContent = editor.getContent();

    if ((value || '') !== currentContent) {
      editor.setContent(value || '');
    }
  }, [value]);

  return (
    <div className="ea-form-field">
      <label>{_x('Description', 'service', 'easy-appointments')}</label>

      <textarea id={editorIdRef.current} defaultValue={value || ''} />

      {error && <div className="ea-error">Error</div>}
    </div>
  );
};

Description.propTypes = {
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  updateFieldValue: PropTypes.func,
  error: PropTypes.bool.isRequired
};

Description.defaultProps = {
  value: '',
  updateFieldValue: f => f
};

export const DescriptionField = () => (
  <Field name="description" component={props => <Description {...props} />} />
);
