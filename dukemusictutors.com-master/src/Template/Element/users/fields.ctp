<?php 
    foreach ($fields as $name) {
        $options = isset($fieldOptions[$name]) ? $fieldOptions[$name] : [];

        $prettyName = isset($specialNames[$name]) ? $specialNames[$name] : str_replace('_', ' ', $name);
        if (isset($optionalFields) && in_array($name, $optionalFields)) {
            $prettyName .= ' (optional)';
            $options['required'] = false;
        }

        echo $this->element('users/field', compact('name', 'prettyName', 'options'));
    }
?>