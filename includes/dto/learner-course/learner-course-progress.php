<?php


namespace Ada_Aba\Includes\Dto\Learner_Course;

use function Ada_Aba\Public\Action\Links\get_request_certificate_link;

class Learner_Course_Progress
{
  private $learner_course;
  private $request_certificate_link;
  private $learner_lessons_progress;

  public function __construct($learner_course, $request_certificate_link, $learner_lessons_progress)
  {
    $this->learner_course = $learner_course;
    $this->request_certificate_link = $request_certificate_link;
    $this->learner_lessons_progress = $learner_lessons_progress;
  }

  public function getCourseName()
  {
    return $this->learner_course->getCourse()->getName();
  }

  public function getCourseUrl()
  {
    return $this->learner_course->getCourse()->getUrl();
  }

  public function isComplete()
  {
    return $this->learner_course->isComplete();
  }

  public function getRequestCertificateLink()
  {
    return $this->request_certificate_link;
  }

  public function getLessons()
  {
    return $this->learner_lessons_progress;
  }
}
