<?php

namespace Drupal\student_assignment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ManageExtensionsForm extends FormBase {

  public function getFormId() {
    return 'manage_extensions_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['allowed_extensions'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Allowed File Extensions'),
      '#description' => $this->t('Enter allowed extensions, separated by commas (e.g., pdf, docx, jpg).'),
      '#default_value' => '',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Extensions'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $allowed_extensions = array_map('trim', explode(',', $form_state->getValue('allowed_extensions')));
    \Drupal::state()->set('allowed_file_extensions', $allowed_extensions);
    $this->messenger()->addStatus($this->t('Allowed extensions have been updated.'));
  }
}

