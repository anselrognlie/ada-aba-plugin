<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Course;

class Progress_Report extends Report_Base
{
  private $course_slug;
  private $course;

  public function __construct($plugin_name, $course_slug)
  {
    parent::__construct($plugin_name);
    $this->course_slug = $course_slug;
    $this->course = Course::get_by_slug($course_slug);
    if (!$this->course) {
      throw new Aba_Exception('Error processing progress report');
    }
  }

  function get_content_type()
  {
    return 'text/csv';
  }

  function get_filename()
  {
    $name = $this->scrub_course($this->course->getName());
    $now = date('Ymd\THis');
    return "progress-report-$name-$now.csv";
  }

  function get_content()
  {
    return "";
  }

  private function scrub_course($name)
  {
    return preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
  }
}
