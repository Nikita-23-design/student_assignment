<?php

namespace Drupal\student_assignment\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;

class TeacherController extends ControllerBase {

  /**
   * Displays a table of student submissions.
   */
  public function viewSubmissions() {
    $header = [
      'roll_no' => $this->t('Student Roll No'),
      'name' => $this->t('Name'),
      'actions' => $this->t('Actions'),
    ];

    $rows = [];

    $query = Database::getConnection()->select('student_submissions', 's')
      ->fields('s', ['student_roll_no', 'student_name', 'id']);
    $results = $query->execute()->fetchAll();

    foreach ($results as $result) {
      $rows[] = [
        'data' => [
          'roll_no' => $result->student_roll_no,
          'name' => $result->student_name,
          'actions' => Link::fromTextAndUrl('Submit marks', Url::fromRoute('student_assignment.submit_marks', ['student_id' => $result->id])),
        ],
      ];
    }

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No submissions found.'),
    ];
  }
}

