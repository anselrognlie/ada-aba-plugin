<?php

namespace Ada_Aba\Includes\Dto\Course;

class Certificate_Details
{
  private $course_name;
  private $learner_name;
  private $completion_date;

  public function __construct($course_name, $learner_name, $completion_date)
  {
    $this->course_name = $course_name;
    $this->learner_name = $learner_name;
    $this->completion_date = $completion_date;
  }

  public function getCourseName()
  {
    return $this->course_name;
  }

  public function getLearnerName()
  {
    return $this->learner_name;
  }

  public function getCompletionDate()
  {
    return $this->completion_date;
  }

  public function getFormattedCompletionDate()
  {
    return date_format($this->completion_date, 'l \t\h\e jS \o\f F Y');
  }

  public function getFilename()
  {
    return 'Ada-Build-Certificate-'
      . date_format($this->completion_date, 'Y-m-d-H-i-s')
      . '.pdf';
  }
}
