<?php

namespace Drupal\student_assignment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Link;

class SubmitMarksForm extends FormBase {

  public function getFormId() {
    return 'submit_marks_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $student_id = NULL) {
    $connection = Database::getConnection();
    $query = $connection->select('student_submissions', 's')
      ->fields('s', ['student_roll_no', 'student_name', 'assignment_file'])
      ->condition('id', $student_id);
    $student_submission = $query->execute()->fetchObject();

    $form['student_roll_no'] = [
      '#type' => 'item',
      '#title' => $this->t('Student Roll No'),
      '#markup' => $student_submission->student_roll_no,
    ];

    $form['student_name'] = [
      '#type' => 'item',
      '#title' => $this->t('Student Name'),
      '#markup' => $student_submission->student_name,
    ];
    $url = \Drupal::service('file_url_generator')->generateAbsoluteString($student_submission->assignment_file);
    $form['assignment_file'] = [
      '#type' => 'item',
      '#title' => $this->t('Assignment File'),
      '#markup' => '<a href="' . $url . '" download>' . basename($student_submission->assignment_file) . '</a>',
    ];

    $form['marks'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Marks'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit Marks'),
    ];

    $form_state->set('student_id', $student_id);

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $student_id = $form_state->get('student_id');
    $marks = $form_state->getValue('marks');

    $connection = Database::getConnection();
    $connection->update('student_submissions')
      ->fields(['marks' => $marks])
      ->condition('id', $student_id)
      ->execute();

    $this->messenger()->addStatus($this->t('Marks have been submitted.'));
    $urr = Url::fromRoute('student_assignment.view_submissions');
    $response = new RedirectResponse($urr);
    $response->send();
  }
}

