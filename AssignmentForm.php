<?php

namespace Drupal\student_assignment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;

class AssignmentForm extends FormBase {

  public function getFormId() {
    return 'assignment_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['student_roll_no'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Roll No'),
      '#required' => TRUE,
    ];

    $form['student_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Name'),
      '#required' => TRUE,
    ];
    $validators = array(
      'file_validate_extensions' => array('pdf','doc','txt'),
    );
    $form['assignment_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Assignment File'),
      '#required' => TRUE,
      '#upload_validators' => $validators,
      '#upload_location' => 'public://student_file/',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit Assignment'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $roll_no = $form_state->getValue('student_roll_no');
    $name = $form_state->getValue('student_name');
    //$file = $form_state->getValue('assignment_file');
    $file = \Drupal::entityTypeManager()->getStorage('file')
            ->load($form_state->getValue('assignment_file')[0]);

    $directory = 'public://' . $roll_no;
    \Drupal::service('file_system')->prepareDirectory($directory, \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY | \Drupal\Core\File\FileSystemInterface::MODIFY_PERMISSIONS);
    
    $ss = $file->get('uri')->value;

    //$file->setFileUri($ss);
    $file->save();

    $connection = Database::getConnection();
    $connection->insert('student_submissions')
      ->fields([
        'student_roll_no' => $roll_no,
        'student_name' => $name,
        'assignment_file' =>  $ss,
      ])
      ->execute();

    $this->messenger()->addStatus($this->t('Assignment submitted successfully.'));
  }
}

