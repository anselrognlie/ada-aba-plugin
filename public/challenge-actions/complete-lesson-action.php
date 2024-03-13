<?php

namespace Ada_Aba\Public\Challenge_Actions;

use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Services\Learner_Lesson_Service;
use Ada_Aba\Public\Action\Links;

use function Ada_Aba\Public\Action\Links\get_progress_link;

class Complete_Lesson_Action extends Action_Base
{
  private $lesson_slug;
  private $lesson;
  private $learner_slug;

  public function __construct($slug, $email, $nonce, $expires_at, $lesson_slug, $learner_slug)
  {
    parent::__construct($slug, $email, $nonce, $expires_at);
    $this->lesson_slug = $lesson_slug;
    $this->learner_slug = $learner_slug;
    $this->lesson = null;
  }

  private function get_lesson()
  {
    if (!$this->lesson) {
      $this->lesson = Lesson::get_by_slug($this->lesson_slug);
    }
    return $this->lesson;
  }

  protected function get_builder()
  {
    return new Complete_Lesson_Action_Builder();
  }

  protected function complete_specific()
  {
    $service = new Learner_Lesson_Service($this->learner_slug);
    $service->complete_lesson($this->lesson_slug);

    Links\redirect_to_progress_page($this->learner_slug, halt: false);
  }

  protected function expired()
  {
    // no special cleanup
  }

  protected function to_payload()
  {
    return json_encode([
      'lesson_slug' => $this->lesson_slug,
      'learner_slug' => $this->learner_slug,
    ]);
  }

  public function get_email_content()
  {
    $subject = 'Ada Build Lesson Completion';

    $lesson = $this->get_lesson();
    $body = $this->get_enroll_email_content($lesson->getName(), get_progress_link($this->learner_slug));

    return [$subject, $body];
  }

  public static function create($email, $lesson_slug, $learner_slug)
  {
    $action = new Complete_Lesson_Action(null, $email, null, null, $lesson_slug, $learner_slug);
    return $action;
  }

  // the function parameters are used within the template
  private function get_enroll_email_content($lesson_name, $progress_link)
  {
    ob_start();
    include __DIR__ . '/../partials/complete-lesson-email.php';
    return ob_get_clean();
  }
}
